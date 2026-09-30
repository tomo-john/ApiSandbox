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

## URLのdog_idとwalkの実際のdog_idのチェック

- `dog_id = 1`の持つ`walk_id`は`1, 2, 3`
- `dog_id = 2`の持つ`walk_id`は`4, 5, 6`

のとき、例えば`show`: `GET /api/dogs/1/walks/4`のリクエストはどうなるのか？

- ルーティングは`{dog}`に`1`、`{walk}`に`4`という2つの値を独立して受け取る
- `show(Walk $walk)`は`$walk`しか引数に取っていないので、Laravelは`{dog}=1`という情報を一切使わない(受け取ってすらいない)
- `$walk(id=4)`はルートモデルバインディングで正常に見つかる(存在するレコードなので)
- `WalkPolicy@view`は`$walk->dog->user_id`(つまり`dog_id=2`のUser)を見て判定する

=> もしログイン中のUserが`dog_id=2`の飼い主なら、`dogs/1/walks/4`という、本来存在しないはずのURLの組み合わせでも、`200 OK`で散歩記録が返ってきてしまう

「そのUserの犬かどうか」は正しくチェックできているが、「URLの`dog_id`とwalkの実際の`dog_id`が一致しているか」は全くチェックされていない。

本来は存在しないURLとして扱われるべきで、`404`を返すのが正しい振る舞い。

これを解消するために、`scoped()`を使用する。

ルーティング:

```php
<?php
// こっちじゃなかった
Route::apiResource('dogs.walks', WalkController::class)->middleware('auth:sanctum')->scopeBindings();

// こっちでいけた
Route::apiResource('dogs.walks', WalkController::class)->middleware('auth:sanctum')->scoped();
```

`scoped()`を付けたことで、Laravelは`{dog}`と`{walk}`という2つのプレースホルダーを、独立した値としてではなく、親子関係のあるものとして解決するようになる。

内部的に`{walk}`を検索する際、`dog_id`が`{dog}`と一致するものを限定で探すという条件が自動的に追加される。

これを使用するためには、前もってリレーションの定義をしておく必要がある。(`Dog`モデルの`walks()`リレーション)

### 上記修正

参考: [公式ドキュメントのここ](https://laravel.com/framework/docs/controllers#restful-scoping-resource-routes)

`Route::resource()`および`Route::apiResource()`のようなリソースルートに対して、

`scopeBindings()`ではなく、`scoped()`を使用するのが正しい。

`scoped([...])`の中身について...ドキュメントの例では`'comment' => 'slug'`となっている。

これは、`id`ではなく`slug`というカラムで検索してねというカスタムキーを指定する例。

今回、`Walk`モデルには`id`以外の検索用カラム(`slug`のようなもの)は用意していない。

本当の原因は、Controllerメソッドの引数に`Dog $dog`を含めていなかったこと。

`scoped()`は「`{walk}`を解決する際に、`{dog}`という親のスコープに基づいて絞り込む」という仕組みである以上、

Laravelが`{dog}`をどのモデルとして認識するかを決めるために、Controller側に`Dog $dog`という受け皿(型情報)が必要だった、と考えるのが自然。

`$dog`を省いてしまうと、Laravelは「`{dog}`をどう解決すればいいか」の手がかりを失い、結果的に`{walk}`のスコープ解決も巻き添えでおかしくなっていた、という推測が今回の実地検証と矛盾しない。

