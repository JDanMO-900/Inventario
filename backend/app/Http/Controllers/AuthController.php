<?php

namespace App\Http\Controllers;

use Log;
use Hash;
use Http;
use Encrypt;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        try {
            $credentials = request(['email', 'password']);

            // Requesting access token
            $response = Http::asForm()->post(getenv('LOGIN_URL') . '/oauth/token', [
                'grant_type' => 'password',
                'client_id' => getenv('CLIENT_ID'),
                'client_secret' => getenv('CLIENT_SECRET'),
                'username' => $credentials['email'],
                'password' => $credentials['password'],
                'scope' => '',
            ]);

            $tokenData = $response->json();

            // Requesting the user info
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $tokenData['access_token'],
            ])->post(getenv('LOGIN_URL') . '/api/userInfo');

            $userData = $response->json();

            // Getting the user info
            $user = User::where('email', $userData['email'])->first();

            // Creating or updating the info
            if (is_null($user)) {
                $user = new User();
                $user->name = $userData['name'];
                $user->email = $userData['email'];
                $user->password = Hash::make($credentials['password']);
                $user->email_verified_at = now();
                if('lalopez@cultura.gob.sv' ==  Str::lcfirst($userData['email']) 
                    OR Str::lcfirst('rramirez@cultura.gob.sv') == $userData['email']
                    OR Str::lcfirst('evaldez@cultura.gob.sv') == $userData['email']
                ) {
                    $user->role_id = Role::where('name', 'Jefe')->first()->id;
                }
                else {
                    $user->role_id = Role::where('name', 'Usuario')->first()->id;
                }                
                $user->save();
            } else {
                $user->name = $userData['name'];
                $user->email = $userData['email'];
                $user->password = Hash::make($credentials['password']);
                $user->save();
            }

            if (!$token = auth()->attempt($credentials)) {
                return response()->json([
                    'message' => 'Credenciales no válidas',
                    'error' => 'Unauthorized'
                ], 401);
            }

            Log::info($token);

            return response()->json([
                'message' => 'Token generado correctamente.',
                'data' => [
                    'backend' => [
                        'access_token' => $token,
                        'token_type' => 'Bearer',
                        'expires_in' => auth()->factory()->getTTL() * 60,
                        'refresh_token' => auth()->setTTL(8600)->claims(['token_type' => 'refresh'])->refresh(),
                    ],
                    'login' => $tokenData,
                    'user' => $user->format(),
                ]
            ]);
        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json([
                'message' => 'No fue posible autenticar el usuario. Revisa tus credenciales de acceso.',
                'error' => $th->getMessage(),
            ], 400);
        }
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginSso()
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user = auth('api')->user()->format();

        return response()->json([
            'status' => 'success',
            'message' => 'Usuario encontrado satisfactoriamente.',
            'user' => $user
        ]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Token generado correctamente.',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
