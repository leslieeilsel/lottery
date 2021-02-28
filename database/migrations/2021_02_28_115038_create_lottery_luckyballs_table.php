<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLotteryLuckyballsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lottery_luckyballs', function (Blueprint $table) {
            $table->id();
            $table->string('draw_num')->comment('期数');
            $table->string('lucky_result')->comment('预测号码');
            $table->string('winning_condition')->nullable()->comment('中奖条件');
            $table->string('winning_amount')->nullable()->comment('中奖金额');
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
        Schema::dropIfExists('lottery_luckyballs');
    }
}
