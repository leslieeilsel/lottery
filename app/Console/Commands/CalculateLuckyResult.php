<?php

namespace App\Console\Commands;

use App\Models\Lottery\History;
use App\Models\Lottery\LuckyBall;
use Illuminate\Console\Command;

class CalculateLuckyResult extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate:lucky';

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
        $unCalculate = LuckyBall::query()->whereNull('winning_condition')->get()->toArray();
        $amountSum = 0;

        foreach ($unCalculate as $key => $item) {
            $result = History::query()->firstWhere('draw_num', $item['draw_num']);
            if ($result) {
                // 分区备用
                $resultDrawNumArray = array_chunk(explode(' ', $result['draw_result']), 5);
                $itemDrawNumArray = array_chunk(explode(' ', $item['lucky_result']), 5);

                $frontend = array_intersect_assoc($resultDrawNumArray[0], $itemDrawNumArray[0]);
                $backend = array_intersect_assoc($resultDrawNumArray[1], $itemDrawNumArray[1]);

                // 中奖条件
                $condition = count($frontend).'+'.count($backend);

                // 计算中奖金额
                $amount = $this->calculateWinningAmount($condition, $result['prize_level_list']);
                $amountSum += str_replace(',', '', $amount);

                $result = LuckyBall::query()
                    ->where('id', $item['id'])
                    ->update([
                        'winning_condition' => $condition,
                        'winning_amount'    => $amount,
                    ]);

                if ($result) {
                    $this->info(($key + 1).' | '.$item['draw_num'].' | '.$item['lucky_result'].' | '.$condition.' | '.$amount);
                }
            }
        }
        $this->info('- Total count: '.count($unCalculate).' !');
        $this->info('- Total amount: ¥'.$amountSum.' !');
    }

    /**
     * 计算中奖金额
     *
     * @param $condition
     * @param $levelList
     *
     * @return int
     */
    public function calculateWinningAmount($condition, $levelList): int
    {
        $role = collect([
            ['level' => '一等奖', 'condition' => '5+2'],
            ['level' => '二等奖', 'condition' => '5+1'],
            ['level' => '三等奖', 'condition' => '5+0'],
            ['level' => '四等奖', 'condition' => '4+2'],
            ['level' => '五等奖', 'condition' => '4+1'],
            ['level' => '六等奖', 'condition' => '3+2'],
            ['level' => '七等奖', 'condition' => '4+0'],
            ['level' => '八等奖', 'condition' => '3+1'],
            ['level' => '八等奖', 'condition' => '2+2'],
            ['level' => '九等奖', 'condition' => '3+0'],
            ['level' => '九等奖', 'condition' => '2+1'],
            ['level' => '九等奖', 'condition' => '1+2'],
            ['level' => '九等奖', 'condition' => '0+2'],
        ]);

        $level = $role->firstWhere('condition', $condition);

        if ($level) {
            $amount = collect(json_decode($levelList, true))->firstWhere('prizeLevel', $level['level']);

            return (int) str_replace(',', '', $amount['stakeAmount']);
        }

        return 0;
    }
}
