# dogsテーブル

- [外部キー制約](https://laravel.com/framework/docs/13.x/migrations#foreign-key-constraints)

## usersテーブルと1対多の関係にする

`foreignId()`は`UNSIGNED BIGINT`として外部キーを指定する。

=> `$table->foreignId('user_id')`

`users`テーブルの`id`が`id()`メソッド作られており、それと型を一致させる必要があるため。

型が合っていないと外部キー制約自体が張れない。

### id()メソッド

符号なしBIGINT(unsignedBIGINT)型で自動増分(オートインクリメント)する主キー(プライマリキー)を生成するエイリアス。

### UNSIGNED BIGINT(符号なしビックイント)

マイナス値を扱わない、非常に大きな整数を保持するための型。

BINGINTは8バイト(64ビット)の整数。符号ありはマイナス約900京～プラス約900京までの範囲を表現可能。

UNSIGNED BIGINTはマイナスの範囲なしで、その分を全てプラスの範囲に割り振る設定。

## 外部キー制約

```php
<?php
Schema::table('posts', function (Blueprint $table) {
    $table->foreignId('user_id')->constrained();
});
```

`constrained()`は外部キー制約(Foreign Key Constraint())を簡単かつ自動的に設定するメソッド。

Laravelの命名規則に従っている場合、`constrained()`は引数を空にするだけで、

カラム名から参照先の「テーブル名」と「主キー(id)」を自動で推測してデータベースに制約を張れる。

## cascadeOnDelete();

今回の場合、Userが削除されるとそのUserが持つDogも削除して欲しいので、`cascadeOnDelete()`も採用する。

## 最終系

```php
<?php
$table->foreignId('user_id')->constrained()->cascadeOnDelete();
```

