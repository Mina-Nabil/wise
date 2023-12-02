<?php

namespace App\Models\Corporates;

use App\Models\Users\AppLog;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Contact extends Model
{
    use HasFactory;
    const MORPH_TYPE = 'corporate_contact';

    protected $table = 'corporate_contacts';
    protected $fillable = [
        "name",
        "job_title",
        "email",
        "phone",
    ];
    ///model functions
    public function setAsDefault()
    {
        try {
            DB::transaction(function () {
                DB::table('corporate_contacts')->where('corporate_id', $this->corporate_id)->update([
                    'is_default'    =>  false
                ]);
                $this->is_default = true;
                $this->save();
            });
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error('Can\'t set contact as default', desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    public function editInfo($name, $job_title = null, $email = null, $phone = null)
    {
        try {
            $this->update([
                "name"  => $name,
                "job_title"  => $job_title,
                "email" => $email,
                "phone"     =>  $phone,
            ]);
            AppLog::info("Adding corporate contact", loggable: $this);
            return false;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Adding corporate contact failed", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    ///relations
    public function corporate(): BelongsTo
    {
        return $this->belongsTo(Corporate::class);
    }
}
