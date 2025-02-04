<?php

namespace App\Models\Insurance;

use App\Models\Users\AppLog;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LineField extends Model
{
    use HasFactory;

    protected $fillable = [
        'line_of_business',
        'field',
        'is_mandatory',
    ];

    public $timestamps = false;

    public static function newLineField($line_of_business, $field , $is_mandatory  = false)
    {
        try {
            $lineField = self::create([
                'line_of_business' => $line_of_business,
                'field' => $field,
                'is_mandatory' => $is_mandatory
            ]);

            return $lineField;
        } catch (Exception $e) {
            report($e);
            AppLog::error('failed to add line fields', $e->getMessage());
            return false;
        }
    }

    public function editField($field , $is_mandatory) {
        try {
            $this->update([
                'field' => $field,
                'is_mandatory' => $is_mandatory
            ]);

            return $this;
        } catch (Exception $e) {
            report($e);
            AppLog::error('failed to edit line fields', $e->getMessage());
            return false;
        }
    }

    ///delete() method should be used in the application

    public function scopeByLineOfBusiness($query, $line_of_business)
    {
        return $query->where('line_of_business', $line_of_business);
    }
    
}
