<?php

namespace App\Models\Insurance;

use App\Models\Payments\Invoice;
use App\Models\Users\AppLog;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class InvoiceExtra extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'invoice_id',
        'title',
        'note',
        'amount',
    ];
    
    /**
     * Create a new invoice extra
     *
     * @param Company $company
     * @param string $title
     * @param float $amount
     * @param string|null $note
     * @return self|false
     */
    public static function createNew(Company $company, string $title, float $amount, ?string $note = null): self|false
    {
        try {
            $extra = new self([
                'company_id' => $company->id,
                'title' => $title,
                'amount' => $amount,
                'note' => $note
            ]);
            
            $extra->save();
            
            AppLog::info('New Invoice Extra added', "Extra '$title' added for company {$company->name}");
            return $extra;
        } catch (Exception $e) {
            AppLog::error("Can't add invoice extra", $e->getMessage());
            report($e);
            return false;
        }
    }
    
    /**
     * Edit an existing invoice extra
     *
     * @param string $title
     * @param float $amount
     * @param string|null $note
     * @return bool
     */
    public function editInfo(string $title, float $amount, ?string $note = null): bool
    {
        // Don't allow editing if already linked to an invoice
        if ($this->invoice_id) {
            AppLog::warning('Cannot edit invoice extra', "Extra '$this->title' is already linked to an invoice");
            return false;
        }
        
        try {
            $this->update([
                'title' => $title,
                'amount' => $amount,
                'note' => $note
            ]);
            
            AppLog::info('Invoice Extra updated', "Extra '$title' updated");
            return true;
        } catch (Exception $e) {
            AppLog::error("Can't update invoice extra", $e->getMessage());
            report($e);
            return false;
        }
    }
    
    /**
     * Delete an invoice extra
     *
     * @return bool
     */
    public function deleteExtra(): bool
    {
        // Don't allow deletion if already linked to an invoice
        if ($this->invoice_id) {
            AppLog::warning('Cannot delete invoice extra', "Extra '$this->title' is already linked to an invoice");
            return false;
        }
        
        try {
            $this->delete();
            
            AppLog::info('Invoice Extra deleted', "Extra '$this->title' deleted");
            return true;
        } catch (Exception $e) {
            AppLog::error("Can't delete invoice extra", $e->getMessage());
            report($e);
            return false;
        }
    }
    
    /**
     * Link this extra to an invoice
     *
     * @param Invoice $invoice
     * @return bool
     */
    public function linkToInvoice(Invoice $invoice): bool
    {
        // Check if already linked to an invoice
        if ($this->invoice_id) {
            AppLog::warning('Cannot link invoice extra', "Extra '$this->title' is already linked to invoice #$this->invoice_id");
            return false;
        }
        
        try {
            $this->update([
                'invoice_id' => $invoice->id
            ]);
            
            AppLog::info('Invoice Extra linked', "Extra '$this->title' linked to invoice #{$invoice->serial}");
            return true;
        } catch (Exception $e) {
            AppLog::error("Can't link invoice extra", $e->getMessage());
            report($e);
            return false;
        }
    }
    
    /**
     * Get the company associated with the invoice extra
     *
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    
    /**
     * Get the invoice associated with the invoice extra
     *
     * @return BelongsTo
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
}
