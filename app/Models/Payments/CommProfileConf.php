<?php

namespace App\Models\Payments;

use App\Models\Insurance\Company;
use App\Models\Insurance\Policy;
use App\Models\Offers\OfferOption;
use App\Models\Users\AppLog;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\DB;

class CommProfileConf extends Model
{
    const MORPH_TYPE = 'comm_profile_conf';

    use HasFactory;

    const FROM_NET_PREM = 'net_premium';
    const FROM_NET_COMM = 'net_commission';
    const FROM_SUM_INSURED = 'sum_insured';

    const FROMS = [self::FROM_NET_PREM, self::FROM_NET_COMM, self::FROM_SUM_INSURED];

    public $fillable = [
        "percentage", "from", "line_of_business", "order"
    ];
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('order', 'asc');
        });
    }

    ///model functions
    public function matches(OfferOption $option)
    {
        $this->loadMissing('condition');
        $option->loadMissing('policy');
        if ($this->condition_type) {
            if ($this->condition_type == Policy::MORPH_TYPE) {
                return $this->condition_id == $option->policy->id;
            } else if ($this->condition_type == Company::MORPH_TYPE) {
                return $this->condition_id == $option->policy->company_id;
            }
        } else if ($this->line_of_business) {
            return $this->line_of_business == $option->policy->business;
        } else return true;
    }

    public function editInfo(
        $percentage,
        $from
    ) {
        try {
            AppLog::info("Updating comm profile conf", loggable: $this);
            $this->update([
                "percentage"    =>  $percentage,
                "from"          =>  $from
            ]);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't update comm profile conf", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    public function moveUp()
    {
        $this->loadMissing('comm_profile', 'comm_profile.configurations');
        $sorted_confs = $this->comm_profile->configurations->sortByDesc('order');
        $swap = false;
        foreach ($sorted_confs as $conf) {
            if ($swap) {
                $tmpOrder = $conf->order;
                $conf->order = $this->order;
                $this->order = $tmpOrder;
                try {
                    DB::transaction(function () use ($conf) {
                        $conf->save();
                        $this->save();
                    });
                    AppLog::info('Orders adjusted', null, $this->comm_profile);
                    return true;
                } catch (Exception $e) {
                    report($e);
                    AppLog::error("Can't adjust order", $e->getMessage(), $this->comm_profile);
                    return false;
                }
            }
            if ($conf->id == $this->id) {
                $swap = true;
            }
        }
        return true;
    }

    public function moveDown()
    {
        $this->loadMissing('comm_profile', 'comm_profile.configurations');
        $sorted_confs = $this->comm_profile->configurations->sortBy('order');
        $swap = false;
        foreach ($sorted_confs as $conf) {
            if ($swap) {
                $tmpOrder = $conf->order;
                $conf->order = $this->order;
                $this->order = $tmpOrder;
                try {
                    DB::transaction(function () use ($conf) {
                        $conf->save();
                        $this->save();
                    });
                    AppLog::info('Orders adjusted', null, $this->comm_profile);
                    return true;
                } catch (Exception $e) {
                    report($e);
                    AppLog::error("Can't adjust order", $e->getMessage(), $this->comm_profile);
                    return false;
                }
            }
            if ($conf->id == $this->id) {
                $swap = true;
            }
        }
        return true;
    }

    public function deleteConfiguration()
    {
        try {
            $this->delete();
            AppLog::info('Comm Profile configuration deleted', loggable: $this);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't delete Comm Profile configuration", loggable: $this);
            return false;
        }
    }

    ///attributes
    public function getConditionTitleAttribute(){
        if($this->line_of_business === null && $this->condition_type == null) return 'Default';

        if($this->line_of_business) return ucwords(str_replace('_', ' ', $this->line_of_business));

        $this->loadMissing('condition');

        if($this->condition_type == Policy::MORPH_TYPE){
            $this->loadMissing('condition.company');
            return $this->condition->company->name  . " - " . $this->condition->name;
        } else {
            return $this->condition->name ;
        }
    }

    ///relations
    public function comm_profile(): BelongsTo
    {
        return $this->belongsTo(CommProfile::class);
    }

    public function condition(): MorphTo
    {
        return $this->morphTo();
    }
}
