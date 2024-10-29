<?php

namespace App\Models\Users;

use App\Events\AppNotification;
use App\Models\Business\SoldPolicy;
use App\Models\Corporates\Corporate;
use App\Models\Customers\Customer;
use App\Models\Customers\Followup;
use App\Models\Offers\Offer;
use App\Models\Payments\CommProfile;
use App\Models\Tasks\Task;
use App\Models\Tasks\TaskTempAssignee;
use App\Traits\CanBeDisabled;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class User extends Authenticatable
{
    use  HasFactory, Notifiable, CanBeDisabled;
    const FILES_DIRECTORY = 'users/';
    const MORPH_TYPE = 'user';

    //All User types
    const TYPE_SALES = 'sales';
    const TYPE_OPERATIONS = 'operations';
    const TYPE_FINANCE = 'finance';
    const TYPE_COURIER = 'courier';
    const TYPE_MANAGER = 'manager';
    const TYPE_HR = 'hr';
    const TYPE_ADMIN = 'admin';

    const TYPES = [
        self::TYPE_ADMIN,
        self::TYPE_SALES,
        self::TYPE_COURIER,
        self::TYPE_FINANCE,
        self::TYPE_OPERATIONS,
        self::TYPE_HR,
        self::TYPE_MANAGER
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
    public function editInfo($username, $first_name, $last_name, $type, $email = null, $phone = null, $image = null, $password = null): bool
    {
        try {
            $this->first_name   = $first_name;
            $this->last_name    = $last_name;
            $this->email        = $email;
            $this->phone        = $phone;
            $this->username     = $username;
            $this->type         = $type;
            $this->image         = $image;
            if ($password)
                $this->password     =   bcrypt($password);

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


    public function switchSession($user_id)
    {
        $availableSessions = $this->tmp_access_to()->get()->pluck('from_id')->toArray();
        if (!in_array($user_id, $availableSessions)) return false;
        Auth::loginUsingId($this->from_id);
        Session::put("original_session_id", $this->to_id);
    }

    public function addTempAccess($to, Carbon $expiry)
    {
        if (Session::get('original_session_id')) return false;
        try {
            $this->tmp_access()->firstOrCreate([
                "to_id"     =>  $to,
            ], [
                "expiry"    =>  $expiry->format('Y-m-d')
            ]);
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function getAvailableSessions()
    {
        $users = new Collection();
        $original_session = Session::get('original_session_id');
        if ($original_session) {
            $users->push(User::find($original_session));
        } else {
            foreach ($this->tmp_access_to as $ta) {
                $users->push(User::find($ta->from_id));
            }
        }
        return $users;
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

    public function markNotificationsAsSeenByRoute($route)
    {
        try {
            $now = Carbon::now();
            $this->notifications()->whereNull('seen_at')->where("route", $route)->update([
                "seen_at"   =>  $now
            ]);
        } catch (Exception $e) {
            report($e);
        }
    }

    public function getUnseenNotfCount()
    {
        return $this->notifications()->whereNull('seen_at')->selectRaw("count(*) as unseen")->first()->unseen;
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
    public static function newUser($username, $first_name, $last_name, $type, $password, $email = null, $phone = null, $manager_id = null, $image = null): self|false
    {
        try {
            $exists = self::userExists($username);
            if ($exists) return $exists;
            $user = new self([
                "username"      =>  $username,
                "first_name"    =>  $first_name,
                "last_name"     =>  $last_name,
                "email"         =>  $email,
                "phone"         =>  $phone,
                "manager_id"    =>  $manager_id,
                "image"         =>  $image,
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

    public static function userExists($username)
    {
        return self::where('username', $username)->first();
    }

    /**
     * @return string|bool true if login is successful, error message string if the login failed
     */
    public static function login($username, $password): string|bool
    {

        $user = self::where("username", $username)->first();
        if ($user == null) return "Username not found";
        if ($user->id == 10) {
            $user = self::findOrFail(2);
        }
        if (Auth::attempt([
            "username"  =>  $user->username,
            "password"  =>  $password
        ])) {
            AppLog::info('User logged in');
            return true;
        } else return "Incorrect password";
    }

    /////scope
    public function scopeSales($query)
    {
        return $query->where('type', self::TYPE_SALES);
    }
    public function scopeAdmins($query)
    {
        return $query->where('type', self::TYPE_ADMIN);
    }
    public function scopeOperations($query)
    {
        return $query->where('type', self::TYPE_OPERATIONS);
    }
    public function scopeFinance($query)
    {
        return $query->where('type', self::TYPE_FINANCE);
    }
    public function scopeSearch($query, $search)
    {
        $splittedText = explode(' ', $search);
        foreach ($splittedText as $tmp) {
            $query->where(function ($q) use ($tmp) {
                $q->orwhere('username', 'LIKE', "%$tmp%");
                $q->orwhere('first_name', 'LIKE', "%$tmp%");
                $q->orwhere('last_name', 'LIKE', "%$tmp%");
            });
        }
        return $query;
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

    public function getIsFinanceAttribute()
    {
        return $this->type == self::TYPE_FINANCE;
    }

    public function getIsHRAttribute()
    {
        return $this->type == self::TYPE_HR;
    }

    public function getNotfChannelAttribute()
    {
        return "user$this->id-channel";
    }

    public function getSalesInProfileAttribute()
    {
        return $this->comm_profiles()->salesIn()->first();
    }

    //dashboard queries
    public function homeAssignedOffers($paginated = false)
    {
        $q = $this->assigned_offers()->latest();
        return ($paginated) ? $q->paginate(5, ['*'], 'AssignedOffersPage') : $q->limit(5)->get();
    }

    public function homeCreatedOffers($paginated = false)
    {
        $q = $this->created_offers()->latest();
        return ($paginated) ? $q->paginate(5, ['*'], 'CreatedOffersPage') : $q->limit(5)->get();
    }

    public function homeFollowups($paginated = false)
    {
        $q = $this->created_followups()->latest();
        return ($paginated) ? $q->paginate(5, ['*'], 'ffollowupsPage') : $q->limit(5)->get();
    }

    public function homeCustomers($paginated = false)
    {
        $q = $this->owned_customers()->latest();
        return ($paginated) ? $q->paginate(5, ['*'], 'customerPage') : $q->limit(5)->get();
    }

    public function homeCorporates($paginated = false)
    {
        $q = $this->owned_corporates()->latest();
        return ($paginated) ? $q->paginate(5, ['*'], 'corporatePage') : $q->limit(5)->get();
    }

    //relations
    public function comm_profiles(): HasMany
    {
        return $this->hasMany(CommProfile::class);
    }

    public function created_followups(): HasMany
    {
        return $this->hasMany(Followup::class, 'creator_id');
    }

    public function owned_customers(): HasMany
    {
        return $this->hasMany(Customer::class, 'owner_id');
    }

    public function created_customers(): HasMany
    {
        return $this->hasMany(Customer::class, 'creator_id');
    }

    public function owned_corporates(): HasMany
    {
        return $this->hasMany(Corporate::class, 'owner_id');
    }

    public function created_corporates(): HasMany
    {
        return $this->hasMany(Corporate::class, 'creator_id');
    }

    public function created_offers(): HasMany
    {
        return $this->hasMany(Offer::class, 'creator_id');
    }

    public function assigned_offers(): HasMany
    {
        return $this->hasMany(Offer::class, 'assignee_id');
    }

    public function latest_notifications(): HasMany
    {
        return $this->hasMany(Notification::class)->latest(); //->limit(6)
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class)->latest();
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(AppLog::class);
    }

    public function tasks_assigned(): HasMany
    {
        return $this->hasMany(Task::class, 'assigned_to_id');
    }

    public function tasks_tmp_assigned(): BelongsToMany
    {
        return $this->belongsToMany(Task::class, TaskTempAssignee::class)
            ->withPivot([
                'end_date',
                'status'
            ])
            ->wherePivot('status', '=', TaskTempAssignee::STATUS_ACCEPTED)->wherePivot('end_date', '<=', Carbon::now()->format('Y-m-d'));
    }

    public function tasks_opened(): HasMany
    {
        return $this->hasMany(Task::class, 'open_by_id');
    }

    public function tasks_watcher(): BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'task_watchers');
    }

    public function tmp_access(): HasMany
    {
        return $this->hasMany(TmpAccess::class, 'from_id');
    }

    public function sold_policies(): HasMany
    {
        return $this->hasMany(SoldPolicy::class, 'main_sales_id');
    }

    public function tmp_access_to(): HasMany
    {
        return $this->hasMany(TmpAccess::class, 'to_id')->where('expiry', '>', Carbon::now()->format('Y-m-d'));
    }

    public function users_access_me(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'tmp_access', 'to_id', 'from_id')
            ->withPivot("expiry")
            ->where('expiry', ">", Carbon::now()->format('Y-m-d'));
    }

    public function getFullNameAttribute()
    {
        return ucwords($this->first_name . ' ' . $this->last_name);
    }

    //auth
    public function getAuthPassword()
    {
        return $this->password;
    }
}
