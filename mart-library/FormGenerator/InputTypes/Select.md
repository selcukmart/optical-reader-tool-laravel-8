![select](https://s3.eu-central-1.amazonaws.com/static.testbank.az/uploads/files/15-1618843059-ok-image.png)

###### Bu Sınıf Ne İşe Yarar?

Select listeleme yaparken girilen sql değerine göre verileri seçme işlemini gerçekleştirir ve listeleyip seçim yapmamızı sağlar.

###### Nasıl Kullanılır

```
 [
                'type' => 'select',
                'translate_option' => true,
                'attributes' => [
                    'name' => 'sube_id'
                ],
                'label' => 'Şube',

                'options' => [
                    'data' => [
                        'from' => 'sql',
                        'sql' => $query_table8
                    ]
                ]
            ]
```

Burada options kısmındaki from = sql olduğu için veritabanı sorguya ihtiyaç vardır.
Bazı durumlarda sql yerine from = key_value_array tanımlanır. bu durumlarda dizi elemanı ve karşılığı yer alır.Bunun örneği aşağıdadır.

```
[
'type' => 'select',
'attributes' => [
'name' => 'durum'
],
'options' => [
'data' => [
'from' => 'key_value_array',
'key_value_array' => [
'0' => ___('Kapalı(Pasif)'),
'1' => ___('Açık(Aktif)'),
]
]
],
'label' => 'Durum'
]
```

###### Hangi Durumlarda Kullanılır

formlarda belirlediğimiz listeden seçim yapma durumunda bu özeliği kullanırız

###### Seçeneklerin Çevirisinin Otomatik Yapılması
'translate_option' => true, özelliği ile select içindeki option ların çevirisi otomatik gerçekleşir