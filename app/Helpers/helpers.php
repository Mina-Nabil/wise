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

    public static function printAccountChildren($indentation, $account, &$printed_arr = [])
    {
        if (in_array($account->id, $printed_arr)) return;

        echo "<option value='$account->id'>$indentation$account->name</option>";
        array_push($printed_arr, $account->id);

        foreach ($account->children_accounts as $ac) {
            self::printAccountChildren($indentation . "* ", $ac, $printed_arr);
        }
    }
}
