<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class Helpers
{
    public static function joined($query, $table)
    {
        $joins = $query->getQuery()->joins;
        if ($joins == null) {
            return false;
        }
        foreach ($joins as $join) {
            if ($join->table == $table) {
                return true;
            }
        }
        return false;
    }


    public static function dayInArabic($day)
    {
        switch ($day) {
            case 0:
                return "حد";
            case 1:
                return "اثنين";
            case 2:
                return "ثلاثاء";
            case 4:
                return "اربعاء";
            case 5:
                return "خميس";
            case 6:
                return "جمعه";
            case 7:
                return "سبت";
        }
    }

    public static function printAccountChildren($indentation, $account)
    {
        Log::info("Printing $account->name's children" );
        if(!($indentation || $account->is_top_parent)) return;
        echo "<option value='$account->id'>$indentation$account->name</option>";
        foreach($account->children_accounts as $ac){
            self::printAccountChildren($indentation . "* ", $ac);
        }
    }
}
