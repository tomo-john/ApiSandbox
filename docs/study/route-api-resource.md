# Route::apiResource()

RESTfullな5つのエンドポイントを1行でまとめて定義できる書き方。

## CRUDのHTTPメソッドとURL

| 操作     | HTTPメソッド | URL          |
| -------- | ------------ | ------------ |
| 一覧取得 | GET          | `/dogs`      |
| 詳細取得 | GET          | `/dogs/{id}` |
| 作成     | POST         | `/dogs`      |
| 更新     | PUT/PATCH    | `/dogs/{id}` |
| 削除     | DELETE       | `/dogs/{id}` |

- `PUT`: リソース全体を置き換える(全フィールドを送る想定)
- `PATCH`: 一部のフィールドだけを更新する

## 1行で自動生成

```php
<?php
Route::apiResource('dogs', DogController::class);
```

これだけで、上記の表の5パターンすべてが一括で定義される。

それぞれが、`DogController`の`index`, `show`, `store`, `update`, `destroy`という決まった名前のメソッドにも自動で紐づく。

## Resource Controllers(今回は使用しない)

一般的なCRUDで使用される例。

ルーティング:

```php
<?php
use App\Http\Controllers\DogController;

Route::resource('dogs', DogController::class);
```

コントローラ作成:

```bash
php artisan make:controller DogController --resource
```

処理されるアクション:

| HTTPメソッド | URI              | Action  | Route Name   |
| ------------ | ---------------- | ------- | ------------ |
| GET          | /dogs            | index   | dogs.index   |
| GET          | /dogs/create     | create  | dogs.create  |
| POST         | /dogs            | store   | dogs.store   |
| GET          | /dogs/{dog}      | show    | dogs.show    |
| GET          | /dogs/{dog}/edit | edit    | dogs.edit    |
| PUT/PATCH    | /dogs/{dog}      | update  | dogs.update  |
| DELETE       | /dogs/{dog}      | destroy | dogs.destroy |

=> APIリソースルートではこれに、`create`と`edit`を除いたものと考える

