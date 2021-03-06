<h1 align="center">大乐透预测</h1>


#### Usage

- 同步开奖历史数据

  ```shell
  php artisan sync:history {pageSize=30}
  ```


- 生成幸运球

  ```shell
  php artisan luckyballs {count=1} {draw?}
  ```


- 计算幸运球中奖规则、中奖金额

  ```shell
  php artisan calculate:lucky
  ```



#### TODO

- [ ] 生成 **胆拖式** 模拟号，及下注金额
- [ ] 生成 **复式** 模拟号，及下注金额
- [ ] 优化模拟算法



#### 模拟中奖情况

| 期数  | 模拟次数 | 中奖次数 | 中奖金额 | 中奖率 | 单注最高中奖金额 |
| :---: | :------: | :------: | :------: | :----: | :--------------: |
| 21035 |   1000   |    31    |   165    |  3.1%  |        15        |
| 21034 |   1000   |    26    |   140    |  2.6%  |        15        |
| 21033 |   1000   |    24    |   130    |  2.4%  |        15        |
| 21032 |   1000   |    25    |   125    |  2.5%  |        5         |
| 21031 |   1000   |    39    |   420    |  3.9%  |       200        |
| 21030 |   1000   |    12    |    60    |  1.2%  |        5         |
| 21029 |   1000   |    27    |   155    |  2.7%  |        15        |
| 21028 |   1000   |    30    |   180    |   3%   |        15        |
| 21027 |   1000   |    28    |   170    |  2.8%  |        15        |
| 21026 |   1000   |    32    |   275    |  3.2%  |       100        |



> 购彩有节制，投注需理性！

