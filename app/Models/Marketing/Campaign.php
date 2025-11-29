<?php

namespace App\Models\Marketing;

use App\Models\Corporates\Corporate;
use App\Models\Customers\Customer;
use App\Models\Customers\Followup;
use App\Models\Customers\Profession;
use App\Models\Insurance\Policy;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Exception;

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

    /**
     * Import leads from Excel file
     * 
     * Excel format:
     * Col 1: platform/channel
     * Col 2: interest type (تأمين_صحى, تأمين_على_العربية, تأمين_على_بيتك, اخر)
     * Col 3: email
     * Col 4: full_name (will be split into first_name and last_name)
     * Col 5: phone_number
     * Col 6: job_title (will create or find Profession)
     * 
     * @param string $filePath Path to the Excel file
     * @param int|null $user_id User ID to set as owner_id for all imported leads (only admins can set this)
     * @return array Returns array with 'success' count and 'errors' array
     */
    public function importLeads(string $filePath, ?int $user_id = null): array
    {
        $user = Auth::user();
        
        // Check authorization: only admins can set a custom user_id
        // If non-admin tries to set user_id, set it to null so leads are assigned to logged-in user
        if ($user_id !== null && (!$user || !$user->is_admin)) {
            $user_id = null;
        }

        $results = [
            'success' => 0,
            'errors' => []
        ];

        try {
            $spreadsheet = IOFactory::load($filePath);
            if (!$spreadsheet) {
                throw new Exception('Failed to read file content');
            }

            $activeSheet = $spreadsheet->getActiveSheet();
            $highestRow = $activeSheet->getHighestDataRow();

            // Start from row 2 (skip header row)
            for ($row = 2; $row <= $highestRow; $row++) {
                try {
                    // Read columns
                    $channel = trim($activeSheet->getCell('A' . $row)->getValue() ?? '');
                    $interestType = trim($activeSheet->getCell('B' . $row)->getValue() ?? '');
                    $email = trim($activeSheet->getCell('C' . $row)->getValue() ?? '');
                    $fullName = trim($activeSheet->getCell('D' . $row)->getValue() ?? '');
                    $phone = trim($activeSheet->getCell('E' . $row)->getValue() ?? '');
                    $jobTitle = trim($activeSheet->getCell('F' . $row)->getValue() ?? '');

                    // Skip empty rows
                    if (empty($fullName) && empty($phone) && empty($email)) {
                        continue;
                    }

                    // Validate required fields
                    if (empty($fullName)) {
                        $results['errors'][] = "Row {$row}: Full name is required";
                        continue;
                    }

                    if (empty($phone)) {
                        $results['errors'][] = "Row {$row}: Phone number is required";
                        continue;
                    }

                    // Split full name into first and last name
                    $nameParts = explode(' ', $fullName, 2);
                    $firstName = $nameParts[0] ?? '';
                    $lastName = $nameParts[1] ?? '';

                    if (empty($firstName)) {
                        $results['errors'][] = "Row {$row}: First name cannot be empty";
                        continue;
                    }

                    // Clean phone number (remove "p:" prefix if present)
                    $phone = preg_replace('/^p:/i', '', $phone);
                    $phone = trim($phone);

                    // Check if customer with same phone and first name already exists
                    $existingCustomer = Customer::where('first_name', $firstName)
                        ->whereHas('phones', function ($query) use ($phone) {
                            $query->where('number', $phone);
                        })
                        ->first();

                    if ($existingCustomer) {
                        // Skip this row - customer already exists
                        continue;
                    }

                    // Get or create profession if job_title is provided
                    $professionId = null;
                    if (!empty($jobTitle)) {
                        $profession = Profession::firstOrCreate(
                            ['title' => $jobTitle]
                        );
                        $professionId = $profession->id;
                    }

                    // Map interest type to business constant
                    $business = null;
                    
                    if ($interestType === 'تأمين_صحى') {
                        $business = Policy::BUSINESS_PERSONAL_MEDICAL;
                    } elseif ($interestType === 'تأمين_على_العربية') {
                        $business = Policy::BUSINESS_PERSONAL_MOTOR;
                    } elseif ($interestType === 'تأمين_على_بيتك') {
                        $business = Policy::BUSINESS_HOME;
                    }
                    // If interestType is 'اخر' or empty, don't add interest

                    // Create the lead
                    $customer = Customer::newLead(
                        first_name: $firstName,
                        last_name: $lastName,
                        phone: $phone,
                        email: !empty($email) ? $email : null,
                        profession_id: $professionId,
                        owner_id: $user_id,
                        campaign_id: $this->id,
                        channel: !empty($channel) ? $channel : null
                    );

                    if (!$customer) {
                        $results['errors'][] = "Row {$row}: Failed to create customer";
                        continue;
                    }

                    // Add interest if business type was determined
                    if ($business !== null) {
                        $customer->setInterests([
                            [
                                'business' => $business,
                                'interested' => true,
                                'note' => null
                            ]
                        ]);
                    }

                    $results['success']++;
                } catch (Exception $e) {
                    $results['errors'][] = "Row {$row}: " . $e->getMessage();
                    continue;
                }
            }
        } catch (Exception $e) {
            $results['errors'][] = "File processing error: " . $e->getMessage();
        }

        return $results;
    }
}
