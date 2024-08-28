<?php

namespace App\Models\Accounting;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntryTitle extends Model
{
    use HasFactory;

    protected $table = 'entry_titles';
    protected $fillable = ['name', 'desc'];
    public $timestamps = false;

    ////static functions
    public static function newOrCreateEntry($name)
    {
        try {
            return self::firstOrCreate([
                'name'          =>  $name,
            ]);
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }


    ///model function
    /** 
     * Must show a warning before the edit. That it's going to update title on old journal entries
     */
    public function editTitle($title)
    {
        try {
            $this->title = $title;
            $this->save();
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function editDesc($desc)
    {
        try {
            $this->desc = $desc;
            $this->save();
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    ///relations
    public function entries()
    {
        return $this->hasMany(JournalEntry::class);
    }
}
