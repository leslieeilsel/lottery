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
    protected $signature = 'luckyballs {count=1} {draw?}';

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
     * @return void
     */
    public function handle(): void
    {
        $lastDraw = History::query()->max('draw_num');
        // 获取最近一期期数
        $drawNum = $this->argument('draw') ?? (string) ((int) $lastDraw + 1);

        for ($i = 1; $i <= $this->argument('count'); $i++) {
            // 随机数生成中奖号码
            $luckyResult = $this->luckyBall($drawNum);
            // 预测结果写入数据库
            $luckBall = new \App\Models\Lottery\LuckyBall([
                'draw_num'     => $drawNum,
                'lucky_result' => $luckyResult
            ]);
            $luckBall->save();

            $this->info('- Lucky ball '.$i.' : '.$luckyResult);
        }
    }

    /**
     * 随机算法生成大乐透中奖号码
     *
     * @param $drawNum
     *
     * @return string
     */
    public function luckyBall($drawNum): string
    {
        return implode(' ', $this->analyzeHistory($drawNum)).' '.implode(' ', $this->uniqueRand(1, 12, 2));
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

    /**
     * 分析历史数据，预测幸运球
     *
     * @param $drawNum
     *
     * @return array
     */
    public function analyzeHistory($drawNum): array
    {
        // 上期出现的本期不出现
        $lastDrawRest = History::query()->where('draw_num', $drawNum - 1)->pluck('draw_result')->first();
        // 计算出现次数
        $oddCount = $this->calculateOddTrend($drawNum);

        return $this->checkOddCountRole($oddCount, $lastDrawRest);
    }

    /**
     * 根据最近五期奇数出现次数，推测下一期奇偶数
     *
     * @param $drawNum
     *
     * @return string
     */
    public function calculateOddTrend($drawNum): string
    {
        $last5Draw = History::query()
            ->where('draw_num', '<', $drawNum)->orderByDesc('draw_time')->limit(5)->pluck('draw_result')->toArray();
        $oddCount = [];
        foreach ($last5Draw as $item) {
            $oddCount[] = $this->oddCount(array_slice(explode(' ', $item), 0, 5));
        }

        return $this->trendNextOddCount($oddCount);
    }

    /**
     * 预测下期奇数量
     *
     * @param $oddCount
     *
     * @return string
     */
    public function trendNextOddCount($oddCount): string
    {
        $count = '3';

        // 奇数量限制在 2,3,4
        if ($oddCount[0] === 2) {
            $count = '3,4';
        } elseif ($oddCount[0] === 3) {
            $count = '2,3,4';
        } elseif ($oddCount[0] === 4) {
            $count = '2,3';
        }

        return $count;
    }

    /**
     * 生成前区号码，使其匹配奇数量预测
     *
     * @param $oddCount
     * @param $lastDrawRest
     *
     * @return array
     */
    public function checkOddCountRole($oddCount, $lastDrawRest): array
    {
        $front = $this->uniqueRand(1, 35, 5);
        if ($this->checkHasLastDrawResult($front, array_slice(explode(' ', $lastDrawRest), 0, 5))) {
            $frontOddCount = $this->oddCount($front);
            if (!in_array($frontOddCount, explode(',', $oddCount), false)) {
                $this->checkOddCountRole($oddCount, $lastDrawRest);
            }
        } else {
            $this->checkOddCountRole($oddCount, $lastDrawRest);
        }

        return $front;
    }

    /**
     * 判断是否为奇数
     *
     * @param $num
     *
     * @return int
     */
    public function odd($num): int
    {
        return ($num & 1);
    }

    /**
     * 计算数组内奇数出现的次数
     *
     * @param $array
     *
     * @return int
     */
    public function oddCount($array): int
    {
        return collect($array)->filter(function ($value) {
            return $this->odd((int) $value);
        })->count();
    }

    /**
     * 检测是否存在上期数字，若存在返回 false
     *
     * @param $luckyBall
     * @param $lastBall
     *
     * @return bool
     */
    public function checkHasLastDrawResult($luckyBall, $lastBall): bool
    {
        foreach ($luckyBall as $k => $item) {
            if (in_array($item, $lastBall, false)) {
                return false;
            }
        }

        return true;
    }
}
