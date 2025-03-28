<?php

namespace App\Http\Controllers;
use Encrypt;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function filter(string $email) {
        $user = User::showUser($email);
        return $user;
    }



    public function index(Request $request) {
        $itemsPerPage = $request->itemsPerPage ?? 10;
        $skip = ($request->page - 1) * $request->itemsPerPage;

        // Getting all the records
        if (($request->itemsPerPage == -1)) {
            $itemsPerPage =  User::count();
            $skip = 0;
        }

        $sortBy = (isset($request->sortBy[0]['key'])) ? $request->sortBy[0]['key'] : 'id';
        $sort = (isset($request->sortDesc[0])) ? "asc" : 'desc';

        $search = (isset($request->search)) ? "%$request->search%" : '%%';

        $user = User::allDataSearched($search, $sortBy, $sort, $skip, $itemsPerPage);
        // $user = Encrypt::encryptObject($user, "id");

        $total = User::counterPagination($search);

        return response()->json([
            "message"=>"Registros obtenidos correctamente.",
            "data" => $user,
            "total" => $total,
        ]);
    }

    public function update(Request $request)
    {

        $userData = User::where('id',$request->id)->first();
		$userData->name = $request->name;
        $userData->email = $request->email;
        $userData->role_id = Role::where('name', $request->role_id)->first()->id;

        $userData->save();

        return response()->json([
            "message"=>"Registro modificado correctamente.",
        ]);
    }

    public function store(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        Log::info($user);
        if (is_null($user)) {
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make('Binaes123!');
            $user->role_id = Role::where('name', $request->role_id)->first()->id;

            $user->save();

            return response()->json([
                "message"=>"Registro insertado correctamente.",
            ]);
        }

        return response()->json([
            "message"=> "No fue posible crear el usuario.",
        ]);       
    }

}

   
