# artisanコマンドメモ

## Model(マイグレーション)作成

```bash
php artisan make:model Dog -m
```

- `-m`でマイグレーションファイルも同時に作成
- [モデルクラスの生成](https://laravel.com/framework/docs/eloquent)

## Controller作成

```bash
php artisan make:controller DogController --resource
```
- `resource`は`index`, `create`, `store`, `show`, `edit`, `update`, `destroy`の7つのメソッドを生成
- [コントローラの作成](https://laravel.com/framework/docs/13.x/controllers#main-content)
- [リソースコントローラ](https://laravel.com/framework/docs/13.x/controllers#resource-controllers)

APIでは`create`, `edit`の2つのメソッドは基本的に不要。

今回は、[APIリソースルート](https://laravel.com/framework/docs/13.x/controllers#api-resource-routes)に書かれているこちらを採用。

```bash
php artisan make:controller DogController --api
```

こちらは、`index`, `store`, `show`, `update`, `destroy`の5つのメソッドを生成してくれる。

