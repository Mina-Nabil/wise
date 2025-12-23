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
     * Set account IDs for a key (replaces all existing accounts for this key)
     */
    public static function setAccountIds(string $key, array $accountIds): void
    {
        // Remove existing entries for this key
        self::where('key', $key)->delete();
        
        // Add new entries
        foreach ($accountIds as $accountId) {
            if ($accountId) {
                self::create([
                    'key' => $key,
                    'account_id' => $accountId
                ]);
            }
        }
        
        Cache::forget("account_setting.{$key}");
    }

    /**
     * Add an account to a key
     */
    public static function addAccount(string $key, int $accountId): void
    {
        // Check if already exists
        $exists = self::where('key', $key)
            ->where('account_id', $accountId)
            ->exists();
        
        if (!$exists) {
            self::create([
                'key' => $key,
                'account_id' => $accountId
            ]);
            Cache::forget("account_setting.{$key}");
        }
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
