<?php

namespace App\Console\Commands;

use App\Models\Lottery\History;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SyncHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:history {pageSize=30}';

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
        // 抓取大乐透历史开奖数据
        $history = Http::get('https://webapi.sporttery.cn/gateway/lottery/getHistoryPageListV1.qry', [
            'gameNo'     => 85,
            'provinceId' => 0,
            'pageSize'   => $this->argument('pageSize'),
            'isVerify'   => 1,
            'pageNo'     => 1,
        ]);

        // 转化为数组
        $historyData = $history->json();

        if ($historyData['success']) {
            $insertData = [];
            $historyDataList = $historyData['value']['list'];
            // 获取数据库中已有的期数
            $exitDrawData = History::query()->pluck('draw_num')->toArray();

            foreach ($historyDataList as $draw) {
                // 排除冗余数据
                if (!in_array($draw['lotteryDrawNum'], $exitDrawData, true)) {
                    $insertData[] = [
                        'draw_num'           => $draw['lotteryDrawNum'],                // 期数
                        'unsort_draw_result' => $draw['lotteryUnsortDrawresult'],       // 出球号码
                        'draw_result'        => $draw['lotteryDrawResult'],             // 中奖号码
                        'draw_time'          => $draw['lotteryDrawTime'],               // 开奖日期
                        'prize_level_list'   => json_encode($draw['prizeLevelList']),   // 中奖详情
                    ];
                }
            }
            if ($insertData) {
                // 写入数据
                $saveResult = History::query()->insertOrIgnore($insertData);
                if ($saveResult) {
                    $this->info('- Saved successfully!');
                    $this->info('- A total of '.count($insertData).' data are saved!');
                }
            } else {
                $this->info('- No data to save!');
            }
        }
    }
}
