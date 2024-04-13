<?php

namespace App\Models\Payments;

use App\Models\Insurance\Company;
use App\Models\Insurance\Policy;
use App\Models\Offers\Offer;
use App\Models\Offers\OfferOption;
use App\Models\Users\AppLog;
use App\Models\Users\User;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CommProfile extends Model
{
    use HasFactory;

    const TYPE_SALES_IN = 'sales_in';
    const TYPE_SALES_OUT = 'sales_out';
    const TYPE_OVERRIDE = 'override';

    const TYPES = [
        self::TYPE_SALES_IN,
        self::TYPE_SALES_OUT,
        self::TYPE_OVERRIDE,
    ];

    protected $fillable = [
        'title', 'type', 'per_policy', 'desc', 'user_id'
    ];

    ///static functions
    public static function newCommProfile(
        $type, //enum one of TYPES
        bool $per_policy, //switch
        $user_id = null, //can be linked to user
        string $title = null, //can be null if a user is selected 
        string $desc = null
    ): self|bool {
        assert($user_id !== null || $title != null, "Must include a title or select a user");
        try {
            $formattedTitle = $title;
            if ($user_id) {
                $user = User::findOrFail($user_id);
                $formattedTitle = "$user->username's $type";
            }
            $newComm = new self([
                "type"          =>  $type,
                "per_policy"    =>  $per_policy,
                "user_id"       =>  $user_id,
                "title"         =>  $formattedTitle,
                "desc"          =>  $desc,
            ]);
            AppLog::error("creating new comm profile");
            $newComm->save();
            return $newComm;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't create comm profile", desc: $e->getMessage());
            return false;
        }
    }

    ///model functions
    public function getValidCommissionConf(OfferOption $option): CommProfileConf|false
    {
        $option->loadMissing('policy');
        $this->loadMissing('configurations');
        foreach ($this->configurations as $conf) {
            if ($conf->matches($option)) {
                return $conf;
            }
        }
        return false;
    }

    public function editProfile(
        $type,
        bool $per_policy,
        string $title = null,
        string $desc = null
    ) {
        try {
            $this->update([
                "type"          =>  $type,
                "per_policy"    =>  $per_policy,
                "title"         =>  $title,
                "desc"          =>  $desc,
            ]);
            AppLog::info("Can't edit comm profile",  loggable: $this);
            return $this->save();
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't edit comm profile", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    public function addConfiguration(
        $percentage, //commission percentage
        $from, //from const FROMS
        Policy|Company $condition = null, //include a policy or a company as a condition
        $line_of_business = null // or select a line of business as the condition - line of business is on of Policy::LINES_OF_BUSINESS
    ) {

        assert(($condition && !$line_of_business) || (!$condition && $line_of_business), "Must include only a condition or a line of business");
        
        try {
            AppLog::info("Creating comm profile configuration", loggable: $this);
            $order = $this->configurations()->count() + 1;
            $conf = $this->configurations()->create([
                "percentage"    =>  $percentage,
                "from"          =>  $from,
                "line_of_business" =>  $line_of_business,
                "order"         =>  $order
            ]);
            if ($condition)
                $conf->condition()->associate($condition);
            $conf->save();
            return $conf;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't create comm profile conf", desc: $e->getMessage());
            return false;
        }
    }

    public function addTarget(
        $period, //from const PERIODS
        $amount, //target amount
        $extra_percentage = null
    ) {
        try {
            AppLog::info("Creating comm profile target", loggable: $this);
            $order = $this->targets()->count() + 1;
            $target = $this->targets()->create([
                "period"            =>  $period,
                "amount"            =>  $amount,
                "extra_percentage"  =>  $extra_percentage,
                "order"             =>  $order
            ]);
            $target->save();
            return $target;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't create comm profile target", desc: $e->getMessage());
            return false;
        }
    }

    ///relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function configurations(): HasMany
    {
        return $this->hasMany(CommProfileConf::class);
    }

    public function targets(): HasMany
    {
        return $this->hasMany(Target::class);
    }

    public function offers(): BelongsToMany
    {
        return $this->belongsToMany(Offer::class, "offer_comm_profiles");
    }
}
