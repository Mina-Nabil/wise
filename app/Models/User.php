<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use  HasFactory, Notifiable;

    const TYPE_SALES = 'sales';
    const TYPE_OPERATIONS = 'operations';
    const TYPE_MANAGER = 'manager';
    const TYPE_ADMIN = 'admin';

    const TYPES = [
        self::TYPE_ADMIN, self::TYPE_SALES, self::TYPE_OPERATIONS, self::TYPE_MANAGER
    ];

    protected $fillable = [
        'first_name',
        'last_name',
        'type',
        'password',
    ];

    protected $hidden = ['password'];

    //model functions

    //static functions
    public static function newUser($first_name, $last_name, $type, $password) {
        $user = new self;
        self::query()->when();
    }
}
