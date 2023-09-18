<?php

use App\Models\Users\Note;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Notable
{

    public function addNote($title, $desc = null, int $user_id = null): Note|false
    {
        try {

            $this->notes()->create([
                "user_id"   =>  $user_id,
                "title"     =>  $title,
                "desc"      =>  $desc
            ]);
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'notable');
    }
}
