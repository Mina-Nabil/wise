<?php

namespace App\Models\Customers;

use App\Models\Users\AppLog;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Relative extends Model
{
    use HasFactory;

    const MORPH_TYPE = 'customer_relative';
    protected $table = 'customer_relatives';

    protected $casts = [
        'birth_date' => 'date',
    ];

    protected $fillable = ['customer_id', 'name', 'relation', 'gender', 'phone', 'birth_date'];

    const RELATION_MAIN = 'main';
    const RELATION_WIFE = 'wife';
    const RELATION_MOTHER = 'mother';
    const RELATION_FATHER = 'father';
    const RELATION_BROTHER = 'brother';
    const RELATION_SISTER = 'sister';
    const RELATION_SON = 'son';
    const RELATION_OTHER = 'other';

    const RELATIONS = [
        self::RELATION_MAIN,
        self::RELATION_WIFE,
        self::RELATION_MOTHER,
        self::RELATION_FATHER,
        self::RELATION_BROTHER,
        self::RELATION_SISTER,
        self::RELATION_SON,
        self::RELATION_OTHER,
    ];

    ///model functions
    public function editInfo($name, $relation, $gender = null, $phone = null, $birth_date = null)
    {
        try {
            $this->update([
                "name"      =>  $name,
                "relation"  =>  $relation,
                "gender"    =>  $gender,
                "phone"     =>  $phone,
                "birth_date"    =>  $birth_date,
            ]);
            AppLog::info("Adding customer relative", loggable: $this);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Adding customer relative failed", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    ///relations
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
