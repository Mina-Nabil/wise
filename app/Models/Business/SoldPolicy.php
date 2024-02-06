<?php

namespace App\Models\Business;

use App\Models\Corporates\Corporate;
use App\Models\Customers\Car;
use App\Models\Customers\Customer;
use App\Models\Insurance\Policy;
use App\Models\Offers\Offer;
use App\Models\Tasks\Task;
use App\Models\Tasks\TaskField;
use App\Models\Users\AppLog;
use App\Models\Users\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SoldPolicy extends Model
{
    use HasFactory;

    const MORPH_TYPE = 'sold_policy';

    protected $table = 'sold_policies';
    protected $fillable = [
        'creator_id', 'offer_id', 'policy_id', 'net_rate', 'net_premium',
        'gross_premium', 'installements_count', 'start', 'expiry', 'discount',
        'payment_frequency', 'is_valid', 'customer_car_id', 'insured_value',
        'car_chassis', 'car_plate_no', 'car_engine', 'policy_number'
    ];

    ///model functions
    public function editInfo(Carbon $start, Carbon $expiry, $policy_number, $car_chassis = null, $car_plate_no = null, $car_engine = null): self|true
    {
        $this->update([
            'policy_number' => $policy_number,
            'start' => $start->format('Y-m-d H:i:s'),
            'expiry' => $expiry->format('Y-m-d H:i:s'),
            'car_chassis' => $car_chassis,
            'car_plate_no' => $car_plate_no,
            'car_engine' => $car_engine
        ]);

        try {
            $this->save();
            AppLog::info("Sold Policy edited", loggable: $this);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't edit Sold Policy", desc: $e->getMessage());
            return false;
        }
    }

    public function setAsValid()
    {
        $this->update([
            'active' => 1,
        ]);

        try {
            $this->save();
            AppLog::info("Sold Policy inactivated", loggable: $this);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't invalidate Sold Policy", desc: $e->getMessage());
            return false;
        }
    }

    public function setAsInvalid()
    {
        $this->update([
            'active' => 0,
        ]);

        try {
            $this->save();
            AppLog::info("Sold Policy inactivated", loggable: $this);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't invalidate Sold Policy", desc: $e->getMessage());
            return false;
        }
    }

    public function updatePaymentInfo($insured_value, $net_rate, $net_premium, $gross_premium, $installements_count, $payment_frequency, $discount)
    {
        $this->update([
            'insured_value' => $insured_value,
            'net_rate'      => $net_rate,
            'net_premium'   => $net_premium,
            'gross_premium' => $gross_premium,
            'installements_count'   => $installements_count,
            'payment_frequency'     => $payment_frequency,
            'discount'      => $discount
        ]);

        try {
            $this->save();
            AppLog::info("Sold Policy payment edited", loggable: $this);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't edit Sold Policy payment", desc: $e->getMessage());
            return false;
        }
    }

    public function setNote($note)
    {
        $this->update([
            'note'      => $note
        ]);

        try {
            $this->save();
            AppLog::info("Sold Policy note edited", loggable: $this);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't edit Sold Policy note", desc: $e->getMessage());
            return false;
        }
    }

    public function addEndorsement($due = null, $desc = null, $actions = [])
    {
        $newEndors = $this->addTask(Task::TYPE_ENDORSMENT, "Policy# $this->policy_number endorsement", $desc, $due);
        if (!$newEndors) return false;
        foreach ($actions as $a) {
            //expiry
            //2025-06-01
            $newEndors->addAction($a['column_name'], $a['value']);
        }
        return $newEndors;
    }

    public function addClaim($due = null, $desc = null, $fields = [])
    {
        $newTask = $this->addTask(Task::TYPE_CLAIM, "Policy# $this->policy_number claim", $desc, $due);
        if (!$newTask) return false;
        foreach (TaskField::SALES_CHECKLIST as $s) {
            $newTask->addField($s, "NO");
        }
        foreach ($fields as $f) {
            $newTask->addField($f['title'], $f['value']);
        }
        return $newTask;
    }

    private function addTask($type, $title, $desc, Carbon $due = null): Task|false
    {
        return Task::newTask($title, taskable: $this, desc: $desc, due: $due, assign_to_id_or_type: $this->creator_id, type: $type);
    }

    /**
     * @param array $benefits array of 'benefit' and 'value'
     */
    public function setBenefits(array $benefits = [])
    {
        try {
            DB::transaction(function () use ($benefits) {
                $this->benefits()->delete();
                foreach ($benefits as $b) {
                    $this->addBenefit($b['benefit'], $b['value']);
                }
                AppLog::info("Changing policy benefits", loggable: $this);
            });
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Setting benefits failed", desc: $e->getMessage(), loggable: $this);
            return false;
        };
    }

    public function addBenefit($benefit, $value): SoldPolicyBenefit|false
    {
        try {
            $benefit = $this->benefits()->firstOrCreate([
                "benefit"   =>  $benefit
            ], [
                "value" =>  $value
            ]);
            AppLog::info("Benefit added", loggable: $this);
            return $benefit;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Setting benefits failed", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    /**
     * @param array $benefits array of 'benefit' and 'value'
     */
    public function setExclusions(array $exclusions = [])
    {
        try {
            DB::transaction(function () use ($exclusions) {
                $this->exclusions()->delete();
                foreach ($exclusions as $e) {
                    $this->addExclusion($e['title'], $e['value']);
                }
                AppLog::info("Changing policy exclusions", loggable: $this);
            });
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Setting exclusions failed", desc: $e->getMessage(), loggable: $this);
            return false;
        };
    }

    public function addExclusion($title, $value): SoldPolicyExclusion|false
    {
        try {
            $exclusion = $this->exclusions()->firstOrCreate([
                "title"   =>  $title
            ], [
                "value" =>  $value
            ]);
            AppLog::info("Exclusion added", loggable: $this);
            return $exclusion;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Setting exclusions failed", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }


    ///static functons
    public static function newSoldPolicy(Customer|Corporate $client, $policy_id, $policy_number, $insured_value, $net_rate, $net_premium, $gross_premium, $installements_count, $payment_frequency, Carbon $start, Carbon $expiry, $discount = 0, $offer_id = null, $customer_car_id = null, $car_chassis = null, $car_plate_no = null, $car_engine = null, $is_valid = true, $note = null): self|bool
    {
        $newSoldPolicy = new self([
            'creator_id' => Auth::id() ?? 10,
            'policy_number' => $policy_number,
            'offer_id'      => $offer_id,
            'policy_id'     => $policy_id,
            'insured_value' => $insured_value,
            'net_rate'      => $net_rate,
            'net_premium'   => $net_premium,
            'gross_premium' => $gross_premium,
            'installements_count' => $installements_count,
            'start'         => $start->format('Y-m-d H:i:s'),
            'expiry'        => $expiry->format('Y-m-d H:i:s'),
            'payment_frequency' => $payment_frequency,
            'is_valid'      => $is_valid,
            'customer_car_id' => $customer_car_id,
            'car_chassis'   => $car_chassis,
            'car_plate_no'  => $car_plate_no,
            'car_engine'    => $car_engine,
            'discount'      => $discount,
            'note'          => $note,
        ]);
        $newSoldPolicy->client()->associate($client);
        try {
            $newSoldPolicy->save();
            AppLog::info("New Sold Policy", loggable: $newSoldPolicy);
            return $newSoldPolicy;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't create Sold Policy", desc: $e->getMessage());
            return false;
        }
    }

    ///scopes
    public function scopeUserData($query, $searchText = null)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        $query->select('sold_policies.*')
            ->join('users', "sold_policies.creator_id", '=', 'users.id');

        if ($loggedInUser->type !== User::TYPE_ADMIN) {
            $query->where(function ($q) use ($loggedInUser) {
                $q->where('users.manager_id', $loggedInUser->id)
                    ->orwhere('users.id', $loggedInUser->id);
            });
        }

        $query->when($searchText, function ($q, $v) {
            $q->leftjoin('corporates', function ($j) {
                $j->on('sold_policies.client_id', '=', 'corporates.id')
                    ->where('sold_policies.client_type', Corporate::MORPH_TYPE);
            })->leftjoin('customers', function ($j) {
                $j->on('sold_policies.client_id', '=', 'customers.id')
                    ->where('sold_policies.client_type', Customer::MORPH_TYPE);
            })->groupBy('sold_policies.id');

            $splittedText = explode(' ', $v);

            foreach ($splittedText as $tmp) {
                $q->where(function ($qq) use ($tmp) {
                    //search using customer info
                    $qq->where('customers.name', 'LIKE', "%$tmp%")
                        ->orwhere('customers.email', 'LIKE', "%$tmp%")
                        //search using customer info
                        ->orwhere('corporates.name', 'LIKE', "%$tmp%")
                        ->orwhere('corporates.email', 'LIKE', "%$tmp%")
                        //search using policy info
                        ->orwhere('policy_number', 'LIKE', "%$tmp%")
                        //search using car info
                        ->orwhere('car_chassis', 'LIKE', "%$tmp%")
                        ->orwhere('car_engine', 'LIKE', "%$tmp%")
                        ->orwhere('car_plate_no', 'LIKE', "%$tmp%");
                });
            }
        });
        return $query->latest();
    }

    public function scopeWithTableRelations($query)
    {
        return $query->with('client', 'policy', 'creator', 'customer_car');
    }

    public function scopeWithProfileRelations($query)
    {
        return $query->with(
            'client',
            'policy',
            'creator',
            'customer_car',
            'claims',
            'endorsements',
            'benefits',
            'exclusions'
        );
    }

    ///relations
    public function client(): MorphTo
    {
        return $this->morphTo();
    }

    public function policy(): BelongsTo
    {
        return $this->belongsTo(Policy::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }

    public function customer_car(): BelongsTo
    {
        return $this->belongsTo(Car::class, 'customer_car_id');
    }

    public function claims(): MorphMany
    {
        return $this->morphMany(Task::class, 'taskable')->where('type', Task::TYPE_CLAIM);
    }

    public function endorsements(): MorphMany
    {
        return $this->morphMany(Task::class, 'taskable')->where('type', Task::TYPE_ENDORSMENT);
    }

    public function benefits(): HasMany
    {
        return $this->hasMany(SoldPolicyBenefit::class);
    }

    public function exclusions(): HasMany
    {
        return $this->hasMany(SoldPolicyExclusion::class);
    }
}
