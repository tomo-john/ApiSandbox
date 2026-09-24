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

