## UserとToken作成(tinker)

```php
<?php
// User
$user = App\Models\User::create(['name' => 'API User', 'email' => 'api@example.com', 'password' => 'password1234']);

// Token
$token = $user->createToken('api-sandbox');
```

## Token(User1)

```bash
curl -i -H "Authorization: Bearer 1|bcSfSLmkUWv0JwZ70QHnFQc5moeFQF1O73jKtFDl7a4e8d65" http://localhost:8000/api/user
```

## Dog作成(User: 1)

```php
<?php
$user = App\Models\User::find(1);

$user->dogs()->create(['name' => 'john', 'breed' => 'Golden', 'birthdate' => '2026-09-23', 'weight' => 10]);
$user->dogs()->create(['name' => 'pyonkichi', 'breed' => 'Rabbit', 'birthdate' => '1989-02-08', 'weight' => 2]);
```

## User2とToke・Dog

```php
<?php
// User
> $user = App\Models\User::create(['name' => 'DOG User', 'email' => 'dog@example.com', 'password' => 'password1234']);

= App\Models\User {#8686
    name: "DOG User",
    email: "dog@example.com",
    #password: "\$2y\$12\$X.X8XbBttV/3FUcGDK4enOMepK1ey3Jv5mESkDyujrNuiwVYW5SvC",
    updated_at: "2026-09-26 13:07:02",
    created_at: "2026-09-26 13:07:02",
    id: 2,
  }

// Token
> $token = $user->createToken('dog-sandbox');

= Laravel\Sanctum\NewAccessToken {#8744
    +accessToken: Laravel\Sanctum\PersonalAccessToken {#8733
      name: "dog-sandbox",
      #token: "c88f617fe9c185a208166a566baa94c830cb5f5b7c5aa0af7e405428db178fe2",
      abilities: "[\"*\"]",
      expires_at: null,
      tokenable_id: 2,
      tokenable_type: "App\\Models\\User",
      updated_at: "2026-09-26 13:07:40",
      created_at: "2026-09-26 13:07:40",
      id: 3,
    },
    +plainTextToken: "3|SYVPOfOoUxmM57X9n8o9JWBKJriXBvc9yvKy4pkB61adeb4f", // これをcurlで使う
  }

// Dog
> $user->dogs()->create(['name' => 'xavi', 'breed' => 'dachshund', 'birthdate' => '2010-10-10', 'weight' => 10]);

= App\Models\Dog {#8714
    name: "xavi",
    breed: "dachshund",
    birthdate: "2010-10-10",
    weight: 10,
    user_id: 2,
    updated_at: "2026-09-26 13:10:57",
    created_at: "2026-09-26 13:10:57",
    id: 3,
  }
```

