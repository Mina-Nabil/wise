<?php

namespace App\Models\Insurance;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LineField extends Model
{
    use HasFactory;

    protected $fillable = [
        'line_of_business',
        'field',
    ];

    public $timestamps = false;

    public static function newLineField($line_of_business, $field)
    {
        try {
            $lineField = self::create([
                'line_of_business' => $line_of_business,
                'field' => $field,
            ]);

            return $lineField;
        } catch (Exception $e) {
            report($e);
            return $e->getMessage() ;
        }
    }

    public function editField($field) {
        try {
            $this->update([
                'field' => $field,
            ]);

            return $this;
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function scopeByLineOfBusiness($query, $line_of_business)
    {
        return $query->where('line_of_business', $line_of_business);
    }
    
}
