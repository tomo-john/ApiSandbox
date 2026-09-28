# curlで使うtokenめも

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

```bash
# User1
curl -i -H "Authorization: Bearer 1|bcSfSLmkUWv0JwZ70QHnFQc5moeFQF1O73jKtFDl7a4e8d65" http://localhost:8000/api/dogs
```

