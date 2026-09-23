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

- [コントローラの作成](https://laravel.com/framework/docs/13.x/controllers#main-content)
- [リソースコントローラ](https://laravel.com/framework/docs/13.x/controllers#resource-controllers)

