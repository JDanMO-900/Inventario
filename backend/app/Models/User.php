<?php

namespace App\Models;

use Encrypt;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail, JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    public $incrementing = true;

    protected $fillable = [
        'id',
        'name',
        'last_name',
        // 'dui',
        'email',
        'password',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'created_at',
        'updated_at',
        'remember_token',
        'email_verified_at',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\VerifyEmailQueued);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'model_id');
    }

    public  static function showUser($email){
        Log::info($email);
        return User::select('users.*', 'users.id as id', 'users.email as email', 'role.name as rolName')
        ->join('role', 'users.role_id', '=','role.id')
        ->where('users.email','like', $email)
        ->get();
    }

    public  static function allDataSearched($search, $sortBy, $sort, $skip, $itemsPerPage){
        return User::select('users.*', 'users.id as id',  'users.name as users', 'role.name as role_id')
        ->join('role', 'users.role_id', '=','role.id')
        ->where('users.name','like', $search)
        ->orWhere('users.email','like', $search)
        ->orWhere('role.name','like', $search)
        ->skip($skip)
        ->take($itemsPerPage)
        ->orderBy("users.$sortBy", $sort)
        ->get();
    }

    public static function counterPagination($search)
    {
        return User::select('users.*', 'users.id as id')
        
		->where('users.name', 'like', $search)

        ->count();
    }

    

    public function format()
    {
        return [
            "id" => Encrypt::encryptValue($this->id) ?? null,
            "name" => $this?->name,
            "email" => $this?->email,
            "rol" => $this?->role?->name,
        ];
    }

    public function userHasRole($rolesSearched)
    {
        $roles = $this->getRoleNames();

        foreach ($rolesSearched as $key => $role) {
            foreach ($roles as $key => $roleName) {
                if ($roleName == $role) return true;
            }
        }

        return false;
    }
}
