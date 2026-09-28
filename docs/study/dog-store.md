# DogControllerのstoreメソッド

- HTTPメソッド: `POST`
- URI: `/dogs`
- Action: `store`
- Route Name: `dogs.store`

## storeメソッドがやるべき3ステップ

- リクエストのバリデーション(送られてきたデータが正しい形式か検証)
- バリデーション済みのデータを使ってDogを作成(`$fillable`を活かした一括代入)
- 作成したDogをレスポンスとして返す(ステータスコードはどうする？)

## バリデーション(FormRequest)

### 作成

```bash
php artisan make:request StoreDogRequest
```

=> `app/Http/Requests/StoreDogRequest.php`が生成される。

`FormRequest`はバリデーションと認可の判断を、Controllerから追い出すための専用クラス。

ポイントは2つのメソッドがひな形に含まれている。

- `authorize()`: 認可
- `rules()`: バリデーション

```php
<?php
class StoreDogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'breed' => ['required', 'string', 'max:100'],
            'birthdate' => ['required', 'date', 'before_or_equal:today'],
            'weight' => ['required', 'integer', 'min:1', 'max:150'],
        ];
    }
}
```

今回のStore処理に関しては、現時点では認可は必要ない(誰でも作成可能)ので`true`とした。

### Controllerから呼び出し

- use宣言を追加(`use App\Http\Requests\StoreDogRequest;`)
- storeメソッドの引数の型を変更する(`(StoreDogRequest $request)`)

=> これだけでOK

なんで型を替えるだけでいい？(サービスコンテナが何をしているか？)

- ルーティングが、Controllerメソッド呼び出し前にそのメソッドの引数の型宣言を覗き見る(`リフレクション`)
- 通常の`Request`オブジェクトを作る代わりに、`StoreDogRequest`のインスタンスを生成
- その過程で、`authorize()` -> `rules()`によるバリデーションを自動的に挟みこむ
- `authorize()`が`false`を返せば、この時点で403を返しControllerのメソッドは実行されない
- `rules()`のバリデーションが失敗すれば、422を返しControllerのメソッドは実行されない
- すべて通過したら`store`メソッドの中身が実行
- この時点で受け取った`$request`はバリデーション済みのデータを保証された状態になっている

### バリデーション通過後のデータだけを扱う

- `$request->validated()`を使用する
- `$request->all()`は使用しない

`$request->all()`はリクエストに含まれるすべてのキーをそのまま返す。

悪意のあるリクエストが`rules()`に定義していない余計なキー(例えば`is_admin`のような)を紛れ込ませていた場合、`all()`はそれも含めて返す。

`validated()`は`rules()`で定義したキーだけを返してくれるので、余計なデータが紛れ込む心配がない。

## storeの最終系

```php
<?php
use App\Http\Requests\StoreDogRequest; // 追加

class DogController extends Controller
{
    public function store(StoreDogRequest $request)
    {
        $validated = $request->validated();

        $dog = $request->user()->dogs()->create($validated);

        return response()->json($dog, 201);
    }
```

## curlで確認

```bash
curl -i -H "Authorization: Bearer 1|bcSfSLmkUWv0JwZ70QHnFQc5moeFQF1O73jKtFDl7a4e8d65" \
  -H "Content-Type: application/json" \
  -X POST \
  -d '{"name":"curl_dog","breed":"system","birthdate":"2026-09-28","weight":28}' \
  http://localhost:8000/api/dogs
```

