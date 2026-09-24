# ルートモデルバインディング

- [公式ドキュメント](https://laravel.com/framework/docs/13.x/folio#route-model-binding)

## apiResourceが生成するプレースホルダの名前

`apiResource`が生成するプレースホルダの名前は、`{id}`ではなくリソース名の単数系。(今回なら`{dog}`)

=> `apiResource('dogs', ...)` -> 単数形の`dogs`をプレースホルダー名に使う。(Laravel規約)

なので、DogControllerが`show(string $id)`のままだと引数の不一致によって値が正しく渡ってこない可能性がある。

## ルートモデルバインディングとは

以下の条件にて発動する。

- Controllerメソッドの引数の型を`Dog`型で宣言する(`Dog $dog`)
- 引数名がルートのプレースホルダー名と一致する(`{dog}` <-> `$dog`)

この2つが揃うと、Laravelは裏側で、URLに含まれるIDを使用して`Dog::findOrFail($id)`相当の処理を自動的にやってくれる。

その結果、Dogモデルのインスタンスを引数に渡してくれるという動きをする。

## memo

ルーティング:

```php
<?php
Route::resource('dogs', DogController::class);
```

これで実質、showのルーティングは`GET /dogs/{dog}`となる。

コントローラ:

```php
<?php
// 引数の型を合わせる
public function show(Dog $dog)
{
    return $dog;
}
```


