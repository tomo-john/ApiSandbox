# API Resource

[公式ドキュメント](https://laravel.com/framework/docs/13.x/eloquent-resources#main-content)

作成:

```bash
php artisan make:resource DogResource
```

- `app/Http/Resources/DogResource.php`が生成
- `app/Http/ResourcesIlluminate\Http\Resources\Json\JsonResource`クラスを継承

## 基本

今の`Dog`のレスポンスは、`return $dog;`でモデルをそのまま返しており、DBのカラム構成がそのままAPIの形になっている。

- カラム名を変えたら、APIの形も変わりクライアント側が壊れる
- 返したくないカラムがあっても、モデル側で`$hidden`を使うくらいしか手がない
- `age`(年齢)のような、DBにない計算値を足したい場合の置き場がない

API Resourceは「モデル」と「APIが返すJSONの形」の間に1枚、変換用のクラスを挟む仕組み。

DBの都合とAPIの契約(クライアントとの約束)を切り離せるメリットがある。

## parent::toArray($request)

`parent` = 自分が継承している親クラス。

親である、`JsonResource`の`toArray()`を呼んで下さいの意味。

ひな形の`parent::toArray($request)`はモデルの全カラムをそのまま返すという挙動。

今回は返す項目を自分で選ぶのが目的なので、自分で組み立てた配列に置き換える。

## 変えてみる 

```php
<?php
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'breed' => $this->breed,
            'birthdate' => $this->birthdate,
            'weight' => $this->weight,
        ];
    }
```

`JsonResource`の中では、元の`Dog`モデルには`$this`経由でアクセスできる。

`$this->name`と書けば、`$dog->name`と同じ値が取れる。

## Controller側で使う方法(Index)

```php
<?php
use App\Http\Resources\DogResource; // use宣言追加

    public function index(IndexDogRequest $request)
    {
        // 略

        return DogResource::collection($dogs);
```

`DogResource`の役割: Dogモデル1個に対して、APIレスポンス用の配列という変換ルールを定義している

=> DogモデルをAPIではこうゆうJSON構造として公開しますよという変換担当

`collection()`は複数のDogモデルを(今回なら)`DogResource`で変換 => 複数件のJSONという処理。

このメソッドに渡すオブジェクトは、`Collection`だけでなく今回のような`ページネーションのオブジェクト`でもOK。

## 単一のインスタンスを返す場合(Store, Show, Update)

公式ドキュメントに従い、`return new DogResource($dog);`に変更した。

この時、ResponseのHTTPステータスコードはLaravel側が自動でやってくれた。

- `store`: `201`
- `show`: `200`
- `update`: `200`

そして、ResponseのJSONは`data`キーでラップされる。([Data Wrapping](https://laravel.com/framework/docs/13.x/eloquent-resources#data-wrapping))

```json
{
    "data": {
        "id": 1,
        "name": "john",
        "breed": "Golden",
        "birthdate": "2026-09-23",
        "weight": 88
    }
}
```

