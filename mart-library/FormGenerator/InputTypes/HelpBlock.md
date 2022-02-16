![file](https://s3.eu-central-1.amazonaws.com/static.testbank.az/uploads/files/15-1619096622-ok-image.png)

###### Bu Sınıf Ne İşe Yarar?

Açıklama satırı veya farklı bir html kodu girilmesi gerektiğinde bu sınıftan yararlanılır.

###### Nasıl Kullanılır

```
                [
                'type' => 'radio',

                'help_block' => 'İndirme Yetkisi Kontrolü aktif
    ve online gruplar seçilmiş ise gönderir',
                'attributes' => [
                    'name' => 'tip10'
                ],
                'options' => [
                    'data' => [
                        'from' => 'key_value_array',
                        'key_value_array' => evetArr()
                    ]
                ],
                'label' => 'Öğrenciye Mail Gönder'
            ],
```

help_block kısmında tırnak içerisinde girilmesi gereken yazı yada html ifadesi belirtilir.

###### Hangi Durumlarda Kullanılır

açıklama yapılması gereken durumlarda yada html kod eklenmesi gerektiğinde bu sınıftan yararlanılır.



