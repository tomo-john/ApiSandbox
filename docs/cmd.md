## token

```bash
curl -i -H "Authorization: Bearer 1|bcSfSLmkUWv0JwZ70QHnFQc5moeFQF1O73jKtFDl7a4e8d65" http://localhost:8000/api/user
```

## UserとToken作成(tinker)

```php
<?php
// User
$user = App\Models\User::create(['name' => 'API User', 'email' => 'api@example.com', 'password' => 'password1234']);

// Token
$token = $user->createToken('api-sandbox');
```

## Dog作成(User: 1)

```php
<?php
$user = App\Models\User::find(1);

$user->dogs()->create(['name' => 'john', 'breed' => 'Golden', 'birthdate' => '2026-09-23', 'weight' => 10]);
$user->dogs()->create(['name' => 'pyonkichi', 'breed' => 'Rabbit', 'birthdate' => '1989-02-08', 'weight' => 2]);
```

