<?php

namespace App\Console\Commands;

use App\Models\Lottery\History;
use Illuminate\Console\Command;

class LuckyBall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'luckyballs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // 随机数生成中奖号码
        $luckyResult = $this->luckyBall();

        // 获取最近一期期数
        $drawNum = History::query()->max('draw_num');

        // 预测结果写入数据库
        $luckBall = new \App\Models\Lottery\LuckyBall([
            'draw_num'     => ((int) $drawNum + 1),
            'lucky_result' => $luckyResult
        ]);
        $luckBall->save();

        $this->info('- Lucky ball: '.$luckyResult);
    }

    /**
     * 随机算法生成大乐透中奖号码
     *
     * @return string
     */
    public function luckyBall(): string
    {
        return implode(' ', $this->uniqueRand(1, 35, 5)).' '.implode(' ', $this->uniqueRand(1, 12, 2));
    }

    /**
     * 生成一定数量的不重复随机数，指定的范围内整数的数量必须比要生成的随机数数量大
     *
     * @param $min
     * @param $max
     * @param $num
     *
     * @return array
     */
    public function uniqueRand($min, $max, $num): array
    {
        $count = 0;
        $return = [];
        while ($count < $num) {
            $return[] = sprintf('%02d', random_int($min, $max));
            $return = array_flip(array_flip($return));
            $count = count($return);
        }
        // 数组升序排列
        asort($return);

        return $return;
    }
}
