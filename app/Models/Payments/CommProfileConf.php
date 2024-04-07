<?php

namespace App\Models\Payments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommProfileConf extends Model
{
    use HasFactory;

    const FROM_NET_PREM = 'net_premium';
    const FROM_NET_COMM = 'net_commission';

    const FROMS = [self::FROM_NET_PREM, self::FROM_NET_COMM];
}
