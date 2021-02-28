<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLotteryHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lottery_history', function (Blueprint $table) {
            $table->id();
            $table->string('draw_num')->comment('期数');
            $table->string('unsort_draw_result')->comment('出球号码');
            $table->string('draw_result')->comment('开奖号码');
            $table->date('draw_time')->comment('开奖日期');
            $table->json('prize_level_list')->comment('中奖情况');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lottery_history');
    }
}
