<?php

namespace App\Models\Users;

use App\Traits\CanBeDisabled;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable
{
    use  HasFactory, Notifiable, CanBeDisabled;


    //All User types
    const TYPE_SALES = 'sales';
    const TYPE_OPERATIONS = 'operations';
    const TYPE_MANAGER = 'manager';
    const TYPE_ADMIN = 'admin';

    const TYPES = [
        self::TYPE_ADMIN, self::TYPE_SALES, self::TYPE_OPERATIONS, self::TYPE_MANAGER
    ];

    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'type',
        'password',
        'phone',
        'email'
    ];

    protected $hidden = ['password', 'remember_token'];


    /////////////functions
    public function editInfo($username, $first_name, $last_name, $type, $email = null, $phone = null): bool
    {
        try {
            $this->first_name   = $first_name;
            $this->last_name    = $last_name;
            $this->email        = $email;
            $this->phone        = $phone;
            $this->username     = $username;
            $this->type         = $type;

            return $this->save();
        } catch (Exception $e) {
            AppLog::error("Updating user failed", $e->getMessage());
            report($e);
            return false;
        }
    }

    public function changePassword($password): bool
    {
        try {
            $this->password = encrypt($password);
            return $this->save();
        } catch (Exception $e) {
            AppLog::error("Changing user password failed", $e->getMessage());
            report($e);
            return false;
        }
    }


    /////////static functions
    public static function newUser($username, $first_name, $last_name, $type, $password, $email = null, $phone = null): self|false
    {
        try {
            $user = new self([
                "username"      =>  $username,
                "first_name"    =>  $first_name,
                "last_name"     =>  $last_name,
                "email"         =>  $email,
                "phone"         =>  $phone,
                "type"          =>  $type,
                "password"      =>  bcrypt($password)
            ]);
            $user->save();
            return $user;
        } catch (Exception $e) {
            AppLog::error("Adding user failed", $e->getMessage());
            report($e);
            return false;
        }
    }

    /**
     * @return string|true true if login is successful, error message string if the login failed
     */
    public static function login($username, $password): string|bool
    {

        $user = self::where("username", $username)->first();
        Log::info($user);
        if ($user == null) return "Username not found";
        if (Auth::attempt([
            "username"  =>  $user->username,
            "password"  =>  $password
        ])) {
            return true;
        } else return "Incorrect password";
    }

    /////attributes
    public function getIsAdminAttribute()
    {
        return $this->type == self::TYPE_ADMIN;
    }

    public function getIsManagerAttribute()
    {
        return $this->type == self::TYPE_MANAGER;
    }

    public function getIsOperationsAttribute()
    {
        return $this->type == self::TYPE_OPERATIONS;
    }

    public function getIsSalesAttribute()
    {
        return $this->type == self::TYPE_SALES;
    }

    //relations
    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function logs()
    {
        return $this->hasMany(AppLog::class);
    }

    //auth
    public function getAuthPassword()
    {
        return $this->password;
    }
}
