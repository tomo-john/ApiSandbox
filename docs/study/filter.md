# クエリ文字列での絞り込み

```php
<?php
public function index(Request $request)
{
    $per_page = $request->query('per_page', 5);

    $breed = $request->query('breed');

    $dogs = $request->user()
                    ->dogs()
                    ->when($breed, function ($query, $breed) {
                        $query->where('breed', $breed);
                    })
                    ->paginate($per_page)
                    ->withQueryString();

    return $dogs;
}
```

## $request->query()

HTTPリクエストのクエリ文字列からのみ値を取得するメソッド。

```php
<?php
$dog = $request->query('dog');
```

引数に指定したキーがない場合、第2引数に指定した値を返す。

```php
<?php
$dog = $request->query('dog', 'john');
```

引数を指定しない場合、全てのクエリ文字列の値を連想配列として取得する。

```php
<?php
$query = $request->query();
```

[公式ドキュメント](https://laravel.com/framework/docs/13.x/requests#retrieving-input-from-the-query-string)

## when()

指定した条件が真の場合に、処理を実行するメソッド。

引数は3つ => `$query->when($value, $callback, $default);`

- 第1引数: 条件判定に使う値
- 第2引数: 第1引数が真の場合に実行する処理
- 第3引数: 第1引数が偽の場合に実行する処理(省略可能)

### クロージャ(無名関数)とは？

クロージャとは、名前を付けずにその場で定義して使える関数のこと。

`when()`では、このクロージャを条件に応じて実行する処理として渡している。

今回使用した引数 => `function($query, $breed) {...}`

- `$query`: Laravelがコールバックに渡すクエリビルダ(`$request->user()->dogs()`)
- `$breed`: Laravelが渡す、第1引数の値(今回は`when()`の第1引数)

```php
<?php
$dog = 'inu';

$callback1 = function($dog) { return $dog;};
$callback2 = function() use ($dog) { return $dog;};

echo $callback1();      // エラー ($callback1に渡す引数がない)

echo $callback1('wan'); // wan ($callback1の引数$dogにwan)

echo $callback2();      // inu ($callback2のuseにより最初に定義した$dog = 'inu'が入る)

echo $callback2('wan'); // inu ($callback2呼び出し時に渡した引数は採用されない)
```

## withQueryString()

ページネーションで現在のURLのクエリ文字列を、ページ移動時のリンクにも引き継ぐメソッド。

