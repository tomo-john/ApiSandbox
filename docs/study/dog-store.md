# DogControllerのstoreメソッド

- HTTPメソッド: `POST`
- URI: `/dogs`
- Action: `store`
- Route Name: `dogs.store`

## storeメソッドがやるべき3ステップ

- リクエストのバリデーション(送られてきたデータが正しい形式か検証)
- バリデーション済みのデータを使ってDogを作成(`$fillable`を活かした一括代入)
- 作成したDogをレスポンスとして返す(ステータスコードはどうする？)

