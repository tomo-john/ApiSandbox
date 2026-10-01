# ページネーション

[公式ドキュメント](https://laravel.com/framework/docs/13.x/pagination#main-content)

## 目的

- 大量のデータを複数のページに分割して扱う仕組み
- Laravelでは`Query Builder`と`Eloquent ORM`のどちらでも利用できる
- APIでは、データを一度に大量取得せず、必要な分だけ返すことでレスポンスのサイズや処理不可を抑えられる

## 3つの主要メソッド

| メソッド         | 特徴                                               |
| ---------------- | -------------------------------------------------- |
| paginate()       | 総件数を取得し、ページ番号や総ページ数も扱える     |
| simplePaginate() | 総件数を数えず、前後のページ移動に必要な情報を扱う |
| cursorPaginate() | カーソルを使って、前後のデータを取得               |

`paginate()`が全部で何件、全部で何ページあるかという情報まで含めて返すので、一番親切で情報量が多い。

その分、総件数を数えるために余分なクエリ(`COUNT`)が1回走る。

`simplePaginate()`は総件数を数えない。「次のページがあるかどうか」だけが分かればいい場面に使う。

`COUNT`クエリがいらない分、`paginate()`より高速。

`cursorPaginate()`はページ番号(`?page=2`)という考え方自体を使わず、

「この地点より後のデータをちょうだい」というカーソル(目印)を使う。

大量データのページ送りで、一番高速。(件数の多いテーブルで後ろのページに行くほど性能差が出る)

## API提供ではどのメソッドを使うのか？

クライアント側が総件数もほしいのか、そうでないのかはわからない。

となれば、リクエストの内容に応じて処理を分岐させてクライアント側欲しい形で渡してあげる。

みたいな考え方があるのがWebとAPIアプリの思想設計の違い。

## 実務でよくある妥協案

`paginate()`と`simplePaginate()`を両方用意して出し分けるのではなく、基本は`paginate()`に統一。

よほど大規模なデータでない限りは、そのオーバーヘッド(`COUNT`クエリ1回分)は許容するという判断。

=> 今回はこれでいく

## paginate()の戻り値の型

```php
<?php
App\Models\Dog::paginate();

= Illuminate\Pagination\LengthAwarePaginator
```

今までの`index`メソッドの戻り値の型は`Illuminate\Database\Eloquent\Collection`。

`paginate()`の戻り値の型は`LengthAwarePaginator`という別の型。

`response()->json()`はJSONに変換する方法を自分で知っているオブジェクトなら何でも渡せる。

`LengthAwarePaginator`もこの仕組みに乗っているクラス。

`DogController`の`index`を以下に変更してみる。

```php
<?php
public function index(Request $request)
{
    return $request->user()->dogs()->paginate(2);
}
```

## レスポンスの中身

- `date`: 実際の`Dog`の配列(今まで`index`で返していた内容そのもの)
- `current_page`, `last_page`, `total`: 今何ページか、全部で何ページか、全部で何件か
- `next_page_url`系: 次のページなどを取得する際にそのまま使えるURL
- `links`: ページ番号のボタンを作る際に使える配列

## 件数をクライアント側から指定できるようにする

`paginate(2)`の`2`は「1ページあたりに含める件数」を指定している。

```php
<?php
public function index(Request $request)
{
    $per_page = $request->query('per_page', 5);

    return $request->user()->dogs()->paginate($per_page);
}
```

`$request->query`の第二引数でデフォルト値を設定。


