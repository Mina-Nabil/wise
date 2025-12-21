<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AccountSetting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'account_id'];

    /**
     * Array of all account keys needed for financial reports
     * Key => Arabic Label
     */
    const ACCOUNT_KEYS = [
        'net_revenues' => 'صافي الإيرادات',
        'cost_of_revenues' => 'تكلفة الحصول علي الايرادات',
        'fixed_assets_depreciation' => 'اهلاك الأصول الثابته',
        'general_administrative_expenses' => 'مصروفات عمومية وإدارية',
        'solidarity_contribution' => 'مساهمة تكافلية',
        'establishment_expenses' => 'مصروفات تأسيس',
        'other_revenues' => 'إيرادات أخرى',
        'interest_income' => 'فوائد دائنة',
        'foreign_exchange' => 'أرباح (خسائر) ترجمة العملات الاجنبية',
        'provisions' => 'مخصصات',
        'deferred_income_tax' => 'ضريبة الدخل المؤجله',
        'income_tax' => 'ضريبة الدخل',
    ];

    /**
     * Relationship to Account model
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Get account ID by key
     */
    public static function getAccountId(string $key): ?int
    {
        return Cache::remember("account_setting.{$key}", 3600, function () use ($key) {
            return self::where('key', $key)->value('account_id');
        });
    }

    /**
     * Get account by key
     */
    public static function getAccount(string $key): ?Account
    {
        $accountId = self::getAccountId($key);
        return $accountId ? Account::find($accountId) : null;
    }

    /**
     * Set account ID for a key
     */
    public static function setAccountId(string $key, ?int $accountId): void
    {
        self::updateOrCreate(
            ['key' => $key],
            ['account_id' => $accountId]
        );
        
        Cache::forget("account_setting.{$key}");
    }

    /**
     * Get all configured settings as key => account_id array
     */
    public static function getAllSettings(): array
    {
        return self::pluck('account_id', 'key')->toArray();
    }

    /**
     * Get all required keys
     */
    public static function getRequiredKeys(): array
    {
        return self::ACCOUNT_KEYS;
    }

    /**
     * Check if all required keys are configured
     */
    public static function isFullyConfigured(): bool
    {
        $configuredKeys = self::whereNotNull('account_id')->pluck('key')->toArray();
        $requiredKeys = array_keys(self::ACCOUNT_KEYS);
        
        return empty(array_diff($requiredKeys, $configuredKeys));
    }

    /**
     * Get missing keys that need configuration
     */
    public static function getMissingKeys(): array
    {
        $configuredKeys = self::whereNotNull('account_id')->pluck('key')->toArray();
        $requiredKeys = array_keys(self::ACCOUNT_KEYS);
        
        return array_diff($requiredKeys, $configuredKeys);
    }
}
