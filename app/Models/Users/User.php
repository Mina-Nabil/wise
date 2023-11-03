<?php

namespace App\Models\Users;

use App\Events\AppNotification;
use App\Models\Tasks\Task;
use App\Models\Tasks\TaskTempAssignee;
use App\Traits\CanBeDisabled;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
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

            if ($this->save()) {
                AppLog::info('User updated', "User $username updated");
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            AppLog::error("Updating user failed", $e->getMessage());
            report($e);
            return false;
        }
    }

    public function pushNotification($title, $message, $route)
    {
        try {
            $this->notifications()->create([
                "sender_id" =>  Auth::user() ? Auth::id() : null,
                "title"     =>  $title,
                "route"     =>  $route
            ]);

            event(new AppNotification([
                "title"     =>  $title,
                "message"   =>  $message,
                "route"     =>  $route
            ], $this));
        } catch (Exception $e) {
            report($e);
        }
    }

    public function changePassword($password): bool
    {
        try {
            $this->password = bcrypt($password);
            if ($this->save()) {
                AppLog::info("Password updated", "New password for $this->username");
                return true;
            } else {
                return false;
            }
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
            AppLog::info('User created', "User $username created");
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
        if ($user == null) return "Username not found";
        if (Auth::attempt([
            "username"  =>  $user->username,
            "password"  =>  $password
        ])) {
            AppLog::info('User logged in');
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

    public function getNotfChannelAttribute()
    {
        return "user$this->id-channel";
    }

    //relations
    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function logs()
    {
        return $this->hasMany(AppLog::class);
    }

    public function tasks_assigned(): HasMany
    {
        return $this->hasMany(Task::class, 'assigned_to_id');
    }

    public function tasks_tmp_assigned(): BelongsToMany
    {
        return $this->belongsToMany(Task::class, TaskTempAssignee::class)->withPivot([
            'end_date', 'status'
        ])->wherePivot('status', '=', TaskTempAssignee::STATUS_ACCEPTED)->wherePivot('end_date', '<=', Carbon::now()->format('Y-m-d'));
    }

    public function tasks_opened(): HasMany
    {
        return $this->hasMany(Task::class, 'open_by_id');
    }

    public function tasks_watcher(): BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'task_watchers');
    }

    //auth
    public function getAuthPassword()
    {
        return $this->password;
    }
}
