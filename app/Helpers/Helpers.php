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

    /**
     * @param bool $leaf_only Show accounts that have children as disabled options - they
     *                        stay visible for hierarchy, but can't be picked. Used by the
     *                        selects that choose an account to post journal entries to.
     */
    public static function printAccountChildren($indentation, $account, &$printed_arr = [], $leaf_only = false)
    {
        if (in_array($account->id, $printed_arr)) return;

        $children = $account->children_accounts;
        $disabled = $leaf_only && $children->count() ? ' disabled' : '';

        echo "<option value='$account->id'$disabled>$indentation$account->name</option>";
        array_push($printed_arr, $account->id);

        foreach ($children as $ac) {
            self::printAccountChildren($indentation . "* ", $ac, $printed_arr, $leaf_only);
        }
    }
}
