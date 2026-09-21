# 日付・時間系メソッド

## メソッド

| Laravel                | DBの型       | 意味                         |
| ---------------------- | ------------ | ---------------------------- |
| `$table->date()`       | `DATE`       | 日付                         |
| `$table->dateTime()`   | `DATETIME`   | 日付＋時刻                   |
| `$table->time()`       | `TIME`       | 時刻だけ                     |
| `$table->timestamp()`  | `TIMESTAMP`  | 日付＋時刻                   |
| `$table->timestamps()` | `DATETIME`等 | `created_at` / `updated_at`  |

## date型とdatetime型

> date = 日付だけ
> datetime = 日付 + 時刻

### date

`2026-09-01`

- 年
- 月
- 日

### datetime

- 年
- 月
- 日
- 時
- 分
- 秒

## サンプル

```php
<?php
$table->date('birthday');

$table->dateTime('published_at');

$table->time('opening_time');

$table->timestamp('deleted_at');
```

## timestamps()がちょっと特殊

これは、`created_at`, `updated_at`の2つのカラムをまとめて作ってくれるメソッド。

DB型は`DATETIME`っぽいけど、Laravelが使う作成日時・更新日時用の2カラムを用意すると覚える。

