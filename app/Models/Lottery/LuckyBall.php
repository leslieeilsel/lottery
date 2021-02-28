<?php

namespace App\Models\Lottery;

use Illuminate\Database\Eloquent\Model;

class LuckyBall extends Model
{
    protected $table = 'lottery_luckyballs';

    protected $fillable = [
        'draw_num',
        'lucky_result',
        'winning_condition',
        'winning_amount',
    ];
}
