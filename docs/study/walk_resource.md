# Walkリソースの追加

`Walk`は`Dog`に紐づくリソース。

```
User (1) --- (多) Dog (1) --- (多) Walk
```

## フロー

基本的には`Dog`と同じステップ:

Migration -> Model/リレーション -> Routing -> Controller -> Policy -> FormRequest

これに加えて、ネストしたリソースという新しい観点が加わる。`/dogs/{dog}/walks`

## Migration

基本は`dogsテーブル`の作成と大きな違いなし。

- 外部キーが`dog_id`
- `walked_at`: 散歩した日時 => `dateTime()`
- `duration_minutes`: 散歩した時間 => `unsignedSmallInteger()`
- `distance_km`: 散歩した距離 => `decimal('distance_km', total: 4, places: 1)`

## Model/リレーション

`Walk`モデルに`fillabel`と`Dog`モデルのリレーション(`belongsTo`)を作成。

既存の`Dog`モデルに`Walk`モデルとのリレーションを(`hasMany`)追加。

## Routing

ネストしたルーティング:

```php
<?php
Route::apiResource('dogs.walks', WalkController::class)->middleware('auth:sanctum');
```

`php artisan route:list`:

```bash
GET|HEAD        api/dogs/{dog}/walks         dogswalksindex ›   WalkController@index
POST            api/dogs/{dog}/walks         dogswalksstore ›   WalkController@store
GET|HEAD        api/dogs/{dog}/walks/{walk}  dogswalksshow ›    WalkController@show
PUT|PATCH       api/dogs/{dog}/walks/{walk}  dogswalksupdate ›  WalkController@update
DELETE          api/dogs/{dog}/walks/{walk}  dogswalksdestroy › WalkController@destroy
```

## Controller

作成:

```bash
php artisan make:controller WalkController --api
```

Controllerでは親と子の両方を受け取る。

```php
<?php
use App\Models\Dog;
use App\Models\Walk;

public function show(Dog $dog, Walk $walk)
{
    // ...
}
```

## Policy

作成:

```bash
php artisan make:policy WalkPolicy --model=Walk
```

認可に必要な`user_id`はDogのリレーション経由で取得する:

```php
<?php
public function view(User $user, Walk $walk): bool
{
    return $user->id === $walk->dog->user_id;
}
```

=> `$walk->dog`の`()`ありなしは注意

- `()`あり -> リレーションそのもの、クエリや関連操作に使用
- `()`なし -> 関連するモデルやコレクションなど、リレーションの結果を取得する

## WalkControllerのIndexでは何を認可する？

`index`は一覧を返すアクションなので、特定の1件の`Walk`インスタンスに対する`view`とは性質が違う。

今回の`index`(この犬の散歩記録一覧を見る)は`Walk`個別の認可(`view`)とは別に、

「そもそもこの`$dog`は、リクエストしてきたUserのDogなのか？」という、`Dog`に対する認可が必要となる。

なので、`WalkController`の`index`で本当にチェックすべきは`WalkPolicy`ではなく、

既に`DogPolicy`に存在している`view`メソッド(このUserはこの`Dog`を見られるか)となる。

=> `WalkController`だから`WalkPolicy`しか使用できないという思い込みは捨てる

```php
<?php
#[Authorize('view', 'dog')]
public function index(Dog $dog): Collection
{
    return $dog->walks()->get();
}
```

この`Dog`を見ることができる`User`だけが、そのDogの散歩記録一覧にアクセスできるという認可。

## Walk関連で使う認可の使い分け

| アクション | 対象 | 使うPolicy        |
| ---------- | ---- | ----------------- |
| index      | Dog  | DogPolicy@view    |
| show       | Walk | WalkPolicy@view   |
| store      | Dog  | DogPolicy@update  |
| update     | Walk | WalkPolicy@update |
| destroy    | Walk | WalkPolicy@delete |

## FormRequest

作成:

```bash
php artisan make:request StoreWalkRequest
php artisan make:request UpdateWalkRequest
```

一般的な命名規則: 「アクション名 + 対象リソース名 + Request」

