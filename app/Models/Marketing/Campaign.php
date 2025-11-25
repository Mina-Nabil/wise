<?php

namespace App\Models\Marketing;

use App\Models\Corporates\Corporate;
use App\Models\Customers\Customer;
use App\Models\Customers\Followup;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'offers',
        'goal',
        'target_audience',
        'marketing_channels',
        'handler',
        'budget',
        'start_date',
        'end_date'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get all customers associated with this campaign
     */
    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    /**
     * Get all corporates associated with this campaign
     */
    public function corporates(): HasMany
    {
        return $this->hasMany(Corporate::class);
    }

    /**
     * Get all followups associated with this campaign
     */
    public function followups(): HasMany
    {
        return $this->hasMany(Followup::class);
    }

    public function handler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handler');
    }

    /**
     * Check if campaign is currently active
     */
    public function getIsActiveAttribute(): bool
    {
        $now = now();
        
        if ($this->start_date && $now->lt($this->start_date)) {
            return false;
        }
        
        if ($this->end_date && $now->gt($this->end_date)) {
            return false;
        }
        
        return true;
    }

    /**
     * Get the client price - budget divided by total clients and corporates
     */
    public function getClientPriceAttribute(): float
    {
        $totalClients = $this->customers()->count() + $this->corporates()->count();
        
        if ($totalClients == 0 || !$this->budget) {
            return 0;
        }
        
        return $this->budget / $totalClients;
    }

    /**
     * Export campaigns report to Excel
     */
    public static function exportReport(?Carbon $start_from = null, ?Carbon $start_to = null, ?Carbon $end_from = null, ?Carbon $end_to = null, ?float $budget_from = null, ?float $budget_to = null, ?string $handler_id = null, ?string $search = null)
    {
        $campaigns = self::query()
            ->when($start_from, function ($query) use ($start_from) {
                $query->where('start_date', '>=', $start_from);
            })
            ->when($start_to, function ($query) use ($start_to) {
                $query->where('start_date', '<=', $start_to);
            })
            ->when($end_from, function ($query) use ($end_from) {
                $query->where('end_date', '>=', $end_from);
            })
            ->when($end_to, function ($query) use ($end_to) {
                $query->where('end_date', '<=', $end_to);
            })
            ->when($budget_from, function ($query) use ($budget_from) {
                $query->where('budget', '>=', $budget_from);
            })
            ->when($budget_to, function ($query) use ($budget_to) {
                $query->where('budget', '<=', $budget_to);
            })
            ->when($handler_id, function ($query) use ($handler_id) {
                $query->where('handler', $handler_id);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('marketing_channels', 'like', '%' . $search . '%')
                      ->orWhere('target_audience', 'like', '%' . $search . '%');
                });
            })
            ->get();

        // Create a simple spreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $activeSheet = $spreadsheet->getActiveSheet();

        // Set headers
        $activeSheet->setCellValue('A1', 'ID');
        $activeSheet->setCellValue('B1', 'Name');
        $activeSheet->setCellValue('C1', 'Description');
        $activeSheet->setCellValue('D1', 'Target Audience');
        $activeSheet->setCellValue('E1', 'Marketing Channels');
        $activeSheet->setCellValue('F1', 'Budget');
        $activeSheet->setCellValue('G1', 'Client Price');
        $activeSheet->setCellValue('H1', 'Handler');
        $activeSheet->setCellValue('I1', 'Start Date');
        $activeSheet->setCellValue('J1', 'End Date');
        $activeSheet->setCellValue('K1', 'Status');

        // Fill data
        $row = 2;
        foreach ($campaigns as $campaign) {
            $handler = '';
            if ($campaign->handler && is_numeric($campaign->handler)) {
                $handlerUser = User::find($campaign->handler);
                $handler = $handlerUser ? $handlerUser->first_name . ' ' . $handlerUser->last_name : '';
            } else {
                $handler = $campaign->handler ?? '';
            }

            $activeSheet->setCellValue('A' . $row, $campaign->id);
            $activeSheet->setCellValue('B' . $row, $campaign->name);
            $activeSheet->setCellValue('C' . $row, $campaign->description);
            $activeSheet->setCellValue('D' . $row, $campaign->target_audience);
            $activeSheet->setCellValue('E' . $row, $campaign->marketing_channels);
            $activeSheet->setCellValue('F' . $row, $campaign->budget);
            $activeSheet->setCellValue('G' . $row, $campaign->client_price);
            $activeSheet->setCellValue('H' . $row, $handler);
            $activeSheet->setCellValue('I' . $row, $campaign->start_date ? $campaign->start_date->format('Y-m-d') : '');
            $activeSheet->setCellValue('J' . $row, $campaign->end_date ? $campaign->end_date->format('Y-m-d') : '');
            $activeSheet->setCellValue('K' . $row, $campaign->is_active ? 'Active' : 'Inactive');
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'campaigns_export_' . date('Y-m-d_H-i-s') . '.xlsx';
        $filePath = storage_path('app/exports/' . $fileName);
        
        // Create exports directory if it doesn't exist
        if (!is_dir(storage_path('app/exports'))) {
            mkdir(storage_path('app/exports'), 0755, true);
        }
        
        $writer->save($filePath);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }
}
