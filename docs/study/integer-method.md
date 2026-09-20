# 整数型メソッド

データの大きさに応じて適切なサイズを選択する。

## メソッドとサイズ

| Laravelのメソッド | SQLの型(MySQL) | サイズ  | 符号なしの範囲                  |
| ----------------- | -------------- | ------- | ------------------------------- |
| `tinyInteger()`   | `TINYINT`      |  1 byte | 0 ～ 255                        |
| `smallInteger()`  | `SMALLINT`     | 2 bytes | 0 ～ 65,535                     |
| `mediumInteger()` | `MEDIUMINT`    | 3 bytes | 0 ～ 16,777,215                 |
| `integer()`       | `INT`          | 4 bytes | 0 ～ 4,294,967,295              |
| `bigInteger()`    | `BIGINT`       | 8 bytes | 0 ～ 18,446,744,073,709,551,615 |

```php
<?php
$table->tinyInteger('status');
$table->smallInteger('age');
$table->mediumInteger('count');
$table->integer('score');
$table->bigInteger('user_id');
```

### unsignedにすると？

`unsigned`を付けることで、負の値を使わない代わりに正の範囲を広げることができる。

## 使い分けのイメージ

最大値ギリギリまで考えて選ぶより、その値の意味で選ぶとイメージしやすい。

| 用途               | 例                 | 型の候補                   |
| ------------------ | ------------------ | -------------------------- |
| 0～255程度の状態値 | `status`           | `tinyInteger`              |
| 年齢など           | `age`              | `unsignedTinyInteger`      |
| 数万程度の数値     | `count`            | `unsignedSmallInteger`     |
| 一般的な整数       | `score`            | `integer`                  |
| ID・外部キー       | `user_id`          | `foreignId` / `bigInteger` |
| 非常に大きな整数   | 大規模カウンタなど | `bigInteger`               |

