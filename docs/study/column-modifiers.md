# Colmun Modifiers (列修飾子)

[公式マニュアル](https://laravel.com/framework/docs/13.x/migrations#column-modifiers)

## 列修飾子とは？

```php
<?php
$table->string('email')->nullable()->unique();
```

この場合、`$table->string('email')`がカラムの型・名前を定義していて、

`->nullable()`, `->unique()`がそのカラムに対する追加の条件・性質を付けている。

## よく使う列修飾子

| 修飾子                 | 意味                     | 使用例                    |
| ---------------------- | ------------------------ | ------------------------- |
| `nullable()`           | `NULL`を許可             | `->nullable()`            |
| `default()`            | デフォルト値             | `->default('draft')`      |
| `unique()`             | 重複を許可しない         | `->unique()`              |
| `unsigned()`           | 符号なし整数             | `->unsigned()`            |
| `comment()`            | カラムコメント           | `->comment('ユーザー名')` |
| `useCurrent()`         | 現在日時をデフォルト値に | `->useCurrent()`          |
| `useCurrentOnUpdate()` | 更新時に現在日時         | `->useCurrentOnUpdate()`  |
| `after()`              | 指定カラムの後ろに配置   | `->after('name')`         |
| `first()`              | 最初に配置               | `->first()`               |

## nullable() と default()

```php
<?php
$table->string('status')
    ->nullable()
    ->default('draft');
```

- 指定なし: `'draft'`
- NULL指定: `NULL`

