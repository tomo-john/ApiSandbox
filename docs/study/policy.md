# Policy

- [公式ドキュメント](https://laravel.com/framework/docs/13.x/authorization#main-content)

## GateとPolicy

- Gateはモデルに紐づかない汎用的な認可ルール向け
- Policyは特定のEloquentモデルに対して認可ルール向け

今回はDogモデルに対して、このUserが所有しているかを認可したいので`Policy`を使用する。

## Policy作成

```bash
php artisan make:policy DogPolicy --model=Dog
```

`--model`オプションによって、表示・作成・更新・削除に関連するポリシーメソッド付きになる。

## Policyをどこで発動させるか

今回はController側で発動させる。

ルーティングは「URLパターン <-> Controller」の静的な対応表を定義する場所。

ルーティングには「今アクセスしようとしている個々のリクエストの中身」という動的な情報は流れてこない。

Controllerのメソッドの中に来て初めて、「ルートモデルバインディングで解決された、具体的な`$dog`インスタンス」と

「認証済みの`$request->user()`」の両方が揃う。

Policyのチェックは「このUserと、このDogインスタンスの組み合わせ」を見て判定するものなので、

両方が揃うタイミング(=Controller内)でしか本質的に発動できない。

## Policyを呼び出す3つの方法(従来)

- Controller内のヘルパーメソッド: `$this->authorize()`
- ファサード経由: `Gate::authorize()`
- Userモデルに生えているメソッド: `$user->can()`

Laravel 13ではこれに`#[Authorize] Attribute`で呼び出す書き方も追加された。

今回は、コントローラ側に`$this->authorize('view', $dog)`みたいに使おうと思ったけど、

Laravel 13では、`app/Http/Controllers/Controller.php`に `AuthorizesRequests`トレイトがデフォルトで組み込まれていない...

```php
<?php
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller
{
    use AuthorizesRequests;
}
```

みたいな構成にする必要がある。

もしくは、Laravel 13で追加された、`#[Authorize(...)]`のController Attributeを使う。

## #[Authorize] Attribute

今回はDogControllerでLaravel 13で追加された`#[Authorize] Attribute`でPolicyを使ってみた。

`DogController`:

```php
<?php
use Illuminate\Routing\Attributes\Controllers\Authorize; // 追加

// show抜粋
class DogController extends Controller
{
    #[Authorize('view', 'dog')]
    public function show(Dog $dog)
    {
        return $dog;
    }

...
```

`DogPolicy`:

```php
<?php
namespace App\Policies;

use App\Models\Dog;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DogPolicy
{
    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, Dog $dog): bool
    {
        return $user->id === $dog->user_id;
    }
...
```

- 参考URL: [公式ドキュメント](https://laravel.com/framework/docs/13.x/controllers#authorization-attributes)

