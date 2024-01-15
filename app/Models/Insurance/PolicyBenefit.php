<?php

namespace App\Models\Insurance;

use App\Models\Users\AppLog;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class PolicyBenefit extends Model
{
    use HasFactory;

    protected $table = 'policy_benefits';
    protected $fillable = ['benefit', 'value'];
    public $timestamps = false;

    const BENEFIT_RISKS = 'الأخطار المغطاه';
    const BENEFIT_WORKSHOPS = 'أماكن الإصلاح';
    const BENEFIT_COVER_PERCENTAGE = 'التحملات ونسبة الاستهلاك المطبقة على المطالبات';
    const BENEFIT_MANDATORY_COVER = 'التحمل الاجباري';
    const BENEFIT_APPLIED_DISCOUNT = 'الخصم المطبق في حالة عدم تقديم أي مطالبات';
    const BENEFIT_ONROAD_HELP = 'خدمات المساعدة على الطريق';
    const BENEFIT_PERSONAL_ACCIDENT = 'الحوادث الشخصية';
    const BENEFIT_OTHER_CARS_ASSISTANCE = 'المسئولية المدنية تجاه سيارات الغير';
    const BENEFIT_POLICE_FILE = 'محضر شرطة';
    const BENEFIT_NATURAL_DISASTER = 'الاخطار الطبيعية (الزلزال / السيول / الفيضان / العواصف)';
    const BENEFIT_STRIKES = 'الإضرابات و أعمال الشغب و الإضرابات المدنية';
    const BENEFIT_CAR_KEYS_LOSS = 'تغطية مفتاح السيارة';
    const BENEFIT_MARKET_PRICE_DIFF = 'فرق القيمة السوقية';
    const BENEFIT_SPARE_CAR = 'السيارة البديله';

    const BENEFITS = [
        self::BENEFIT_RISKS,
        self::BENEFIT_WORKSHOPS,
        self::BENEFIT_COVER_PERCENTAGE,
        self::BENEFIT_MANDATORY_COVER,
        self::BENEFIT_APPLIED_DISCOUNT,
        self::BENEFIT_ONROAD_HELP,
        self::BENEFIT_PERSONAL_ACCIDENT,
        self::BENEFIT_OTHER_CARS_ASSISTANCE,
        self::BENEFIT_POLICE_FILE,
        self::BENEFIT_NATURAL_DISASTER,
        self::BENEFIT_STRIKES,
        self::BENEFIT_CAR_KEYS_LOSS,
        self::BENEFIT_MARKET_PRICE_DIFF,
        self::BENEFIT_SPARE_CAR
    ];

    ///model function
    public function editInfo($benefit, $value)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (
            !($loggedInUser == null && App::isLocal()) && //local seeder code - can remove later
            !$loggedInUser->can('update', $this->policy)
        ) return false;
        try {
            return $this->update([
                "benefit"   =>  $benefit,
                "value"     =>  $value
            ]);
        } catch (Exception $e) {
            report($e);
            AppLog::error("Benefit Update failed", loggable: $this, desc: $e->getMessage());
            return false;
        }
    }

    public function delete()
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (
            !($loggedInUser == null && App::isLocal()) && //local seeder code - can remove later
            !$loggedInUser->can('update', $this->policy)
        ) return false;
        try {
            if (parent::delete()) {
                AppLog::info("Deleted benefit");
            }
        } catch (Exception $e) {
            report($e);
            AppLog::error("Benefit Deletion failed", loggable: $this, desc: $e->getMessage());
            return false;
        }
    }


    ///relations
    public function policy()
    {
        return $this->belongsTo(Policy::class);
    }
}
