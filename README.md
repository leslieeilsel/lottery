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
  php artisan sync:history {pageSize=30}
  ```



#### 模拟中奖情况

| 期数  | 模拟次数 | 中奖次数 | 中奖金额 | 中奖率 |
| :---: | :------: | :------: | :------: | :----: |
| 21021 |   1000   |    14    |    80    |  1.4%  |
| 21020 |   1000   |    18    |    90    |  1.8%  |



#### TODO

- [ ] 支持生成 **胆拖式** 模拟号
- [ ] 支持生成 **复式** 模拟号
- [ ] 优化模拟算法



> 购彩有节制，投注需理性！

