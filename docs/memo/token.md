# curlで使うtokenめも

## token(User1, 2)

User1:

```
1|bcSfSLmkUWv0JwZ70QHnFQc5moeFQF1O73jKtFDl7a4e8d65
```

User2:

```
3|SYVPOfOoUxmM57X9n8o9JWBKJriXBvc9yvKy4pkB61adeb4f
```

curl:

```bash
curl -i -H "Authorization: Bearer <token>" ...
```

## Dog CRUD

```bash
# User1 (Index)
curl -i -H "Authorization: Bearer 1|bcSfSLmkUWv0JwZ70QHnFQc5moeFQF1O73jKtFDl7a4e8d65" http://localhost:8000/api/dogs

# User1 (Show)
curl -i -H "Authorization: Bearer 1|bcSfSLmkUWv0JwZ70QHnFQc5moeFQF1O73jKtFDl7a4e8d65" http://localhost:8000/api/dogs/1

# User1 (Store)
curl -i -H "Authorization: Bearer 1|bcSfSLmkUWv0JwZ70QHnFQc5moeFQF1O73jKtFDl7a4e8d65" -H "Content-Type: application/json" -X POST \
  -d '{"name":"curl_dog","breed":"system","birthdate":"2026-09-28","weight":28}' http://localhost:8000/api/dogs

# User1 (Update)
curl -i -H "Authorization: Bearer 1|bcSfSLmkUWv0JwZ70QHnFQc5moeFQF1O73jKtFDl7a4e8d65" -H "Content-Type: application/json" -X PATCH \
  -d '{"weight":100}' http://localhost:8000/api/dogs/4

# User1 (Delete)
curl -i -H "Authorization: Bearer 1|bcSfSLmkUWv0JwZ70QHnFQc5moeFQF1O73jKtFDl7a4e8d65" -X DELETE http://localhost:8000/api/dogs/4
```

## Walk CRUD

```bash
curl -i -X POST -H "Authorization: Bearer 1|bcSfSLmkUWv0JwZ70QHnFQc5moeFQF1O73jKtFDl7a4e8d65" -H "Content-Type: application/json" \
  -d '{"walked_at":"2026-09-30 13:00","duration_minutes":30,"distance_km":2.5}' http://localhost:8000/api/dogs/1/walks | head -30

curl -i -H "Authorization: Bearer 1|bcSfSLmkUWv0JwZ70QHnFQc5moeFQF1O73jKtFDl7a4e8d65" http://localhost:8000/api/dogs/1/walks/1
```

