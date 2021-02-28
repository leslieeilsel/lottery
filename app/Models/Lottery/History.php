<?php

namespace App\Models\Lottery;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $table = 'lottery_history';

    protected $fillable = [
        'draw_num',
        'unsort_draw_result',
        'draw_result',
        'draw_time',
        'prize_level_list',
    ];
}
