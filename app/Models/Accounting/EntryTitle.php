<?php

namespace App\Models\Accounting;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class EntryTitle extends Model
{
    use HasFactory;

    protected $table = 'entry_titles';
    protected $fillable = ['name', 'desc'];
    public $timestamps = false;

    ////static functions

    /**
     * @param $accounts should be an array of 
     * ['id' => [
     * 'nature' => 'debit' or 'credit' , 
     * 'limit => nullable or double
     * ] ] 
     */
    public static function newOrCreateEntry($name, $desc = null, $accounts = [])
    {
        try {
            $entryTitle = self::firstOrCreate([
                'name'          =>  $name,
            ], [
                'desc'          =>  $desc,
            ]);
            if (count($accounts)) {
                $entryTitle->accounts()->sync(
                    $accounts
                );
            }
            return $entryTitle;
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }


    ///model function
    /** 
     * Must show a warning before the edit. That it's going to update title on old journal entries
     * @param $accounts should be an array of 
     * ['id' => [
     * 'nature' => 'debit' or 'credit' , 
     * 'limit => nullable or double
     * ] ] 
     */
    public function editTitle($title, $desc = null, array $accounts = [])
    {
        try {
            $this->title = $title;
            $this->desc = $desc;
            $this->save();
            if (count($accounts)) {
                $this->accounts()->sync(
                    $accounts
                );
            }
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
    public function entries(): HasMany
    {
        return $this->hasMany(JournalEntry::class);
    }

    public function accounts(): BelongsToMany
    {
        return $this->belongsToMany(Account::class, 'titles_accounts')->withPivot('nature', 'limit');
    }
}
