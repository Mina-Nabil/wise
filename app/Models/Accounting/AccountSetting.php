<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AccountSetting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'account_id', 'calc_type'];

    const CALC_TYPE_ADD = 'add';
    const CALC_TYPE_SUBTRACT = 'subtract';
    const CALC_TYPES = [self::CALC_TYPE_ADD, self::CALC_TYPE_SUBTRACT];

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
     * Get account IDs by key (supports multiple accounts per key)
     */
    public static function getAccountIds(string $key): array
    {
        return Cache::remember("account_setting.{$key}", 3600, function () use ($key) {
            return self::where('key', $key)
                ->whereNotNull('account_id')
                ->pluck('account_id')
                ->toArray();
        });
    }

    /**
     * Get accounts by key (returns collection of Account models)
     */
    public static function getAccounts(string $key)
    {
        $accountIds = self::getAccountIds($key);
        return Account::whereIn('id', $accountIds)->get();
    }

    /**
     * Get accounts with calc_type by key
     */
    public static function getAccountsWithCalcType(string $key): array
    {
        return Cache::remember("account_setting.{$key}.calc", 3600, function () use ($key) {
            return self::where('key', $key)
                ->whereNotNull('account_id')
                ->get()
                ->map(function($setting) {
                    return [
                        'account_id' => $setting->account_id,
                        'calc_type' => $setting->calc_type,
                    ];
                })
                ->toArray();
        });
    }

    /**
     * Set account IDs for a key with calc_types (replaces all existing accounts for this key)
     * @param array $accountsData Array of ['account_id' => id, 'calc_type' => 'add'/'subtract']
     */
    public static function setAccountIds(string $key, array $accountsData): void
    {
        // Remove existing entries for this key
        self::where('key', $key)->delete();
        
        // Add new entries
        foreach ($accountsData as $data) {
            $accountId = is_array($data) ? ($data['account_id'] ?? null) : $data;
            $calcType = is_array($data) ? ($data['calc_type'] ?? self::CALC_TYPE_ADD) : self::CALC_TYPE_ADD;
            
            if ($accountId) {
                self::create([
                    'key' => $key,
                    'account_id' => $accountId,
                    'calc_type' => $calcType,
                ]);
            }
        }
        
        Cache::forget("account_setting.{$key}");
        Cache::forget("account_setting.{$key}.calc");
    }

    /**
     * Add an account to a key with calc_type
     */
    public static function addAccount(string $key, int $accountId, string $calcType = self::CALC_TYPE_ADD): void
    {
        // Check if already exists
        $exists = self::where('key', $key)
            ->where('account_id', $accountId)
            ->exists();
        
        if (!$exists) {
            self::create([
                'key' => $key,
                'account_id' => $accountId,
                'calc_type' => $calcType,
            ]);
            Cache::forget("account_setting.{$key}");
            Cache::forget("account_setting.{$key}.calc");
        }
    }

    /**
     * Update calc_type for a specific account setting
     */
    public static function updateCalcType(string $key, int $accountId, string $calcType): void
    {
        self::where('key', $key)
            ->where('account_id', $accountId)
            ->update(['calc_type' => $calcType]);
        
        Cache::forget("account_setting.{$key}");
        Cache::forget("account_setting.{$key}.calc");
    }

    /**
     * Remove an account from a key
     */
    public static function removeAccount(string $key, int $accountId): void
    {
        self::where('key', $key)
            ->where('account_id', $accountId)
            ->delete();
        
        Cache::forget("account_setting.{$key}");
        Cache::forget("account_setting.{$key}.calc");
    }

    /**
     * Get all configured settings as key => [account_ids] array
     */
    public static function getAllSettings(): array
    {
        $settings = self::whereNotNull('account_id')
            ->get()
            ->groupBy('key');
        
        $result = [];
        foreach ($settings as $key => $items) {
            $result[$key] = $items->pluck('account_id')->toArray();
        }
        
        return $result;
    }

    /**
     * Get all configured settings with calc_type as key => [['account_id' => id, 'calc_type' => type]] array
     */
    public static function getAllSettingsWithCalcType(): array
    {
        $settings = self::whereNotNull('account_id')
            ->get()
            ->groupBy('key');
        
        $result = [];
        foreach ($settings as $key => $items) {
            $result[$key] = $items->map(function($item) {
                return [
                    'account_id' => $item->account_id,
                    'calc_type' => $item->calc_type,
                ];
            })->toArray();
        }
        
        return $result;
    }

    /**
     * Get all required keys
     */
    public static function getRequiredKeys(): array
    {
        return self::ACCOUNT_KEYS;
    }

    /**
     * Check if all required keys are configured (at least one account per key)
     */
    public static function isFullyConfigured(): bool
    {
        $configuredKeys = self::whereNotNull('account_id')
            ->distinct('key')
            ->pluck('key')
            ->toArray();
        $requiredKeys = array_keys(self::ACCOUNT_KEYS);
        
        return empty(array_diff($requiredKeys, $configuredKeys));
    }

    /**
     * Get missing keys that need configuration
     */
    public static function getMissingKeys(): array
    {
        $configuredKeys = self::whereNotNull('account_id')
            ->distinct('key')
            ->pluck('key')
            ->toArray();
        $requiredKeys = array_keys(self::ACCOUNT_KEYS);
        
        return array_diff($requiredKeys, $configuredKeys);
    }
}
