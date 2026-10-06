# 日付の扱い

- DBの`birthdate`は`DATE`型で、取り出すと文字列で返ってくる
- Eloquentは、何も設定しなければ、その文字列をそのまま属性として持つ
- 文字列のままでは「年数を数える」といった日付計算ができない

一方で、`created_at`と`updated_at`は、設定していなくても日付オブジェクト(`Carbon`)として扱える。

`timestamps()`で作られるカラムは、Eloquentが特別扱いして自動で変換しているため。

`birthdate`のような自分で追加した日付カラムについては、Laravelに「これは日付として扱ってね」と伝えてあげる。

## casts

Dogモデルに`casts()`メソッドを追加。(Userモデルを参考)

```php
<?php
    protected function casts(): array
    {
        return [
            'birthdate' => 'date',
        ];
    }
```

```php
<?php
// 変更前(文字列が返る)
> App\Models\Dog::find(1)->birthdate;

= "2026-09-23"

// 変更後(Carbonクラスが返る)
= Illuminate\Support\Carbon @1790089200 {#8177
    date: 2026-09-23 00:00:00.0 Asia/Tokyo (+09:00),
  }
```

## ここまでの問題点

- DB上の日付は9/23(`date: 2026-09-23 00:00:00.0 Asia/Tokyo (+09:00)`)
- JSON Responseでは日付がずれる(`"birthdate": "2026-09-22T15:00:00.000000Z",`)
- JSON Responseでは`"2026-09-23"`のように返したい

=> DBでは`DATE`、Laravel内部では`Carbon`、APIレスポンスでは`"Y-m-d"`

## APIレスポンスの形式はDogResourceで

`DogResource`の役割はAPIが返す形を決める場所。

[日付のフォーマット](https://laravel.com/framework/docs/13.x/eloquent-serialization#customizing-the-default-date-format)

`DogResource`の`birthdate`の箇所を`'birthdate' => $this->birthdate->format('Y-m-d'),`へ変更。

## 年齢を算出する

Dogモデルにアクセサリーを設定。

Laravel13では以下の流れ。[Attribute](https://laravel.com/framework/docs/13.x/eloquent-mutators#defining-an-accessor)

```
Dogモデル -> ageという属性にアクセスされたら、どうゆう値を返す？

Attributeオブジェクト -> getに「取得されたときの処理」を定義
```

DogModel:

```php
<?php
use Illuminate\Database\Eloquent\Casts\Attribute;

    protected function age(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->birthdate->age,
        );
    }
```

モデル側に定義したアクセサリーを`DogResource`から利用する。

=> `'age' => $this->age,`の追加

