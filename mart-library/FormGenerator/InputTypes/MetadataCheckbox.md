![metadata-checkbox](https://s3.eu-central-1.amazonaws.com/static.testbank.az/uploads/files/15-1618905694-ok-image.png)

###### Bu Sınıf Ne İşe Yarar?

Birden fazla seçim yapılması gerektiren listeler için kullanılır

###### Nasıl Kullanılır

```
               [
                'type' => 'metadata_checkbox',
                'dependend' => [
                    'group' => 'tip12',
                    'dependend' => 'tip12-1'
                ],
                'veri' => [
                    'tip' =>  $tip_verisi['kume_id'],
                    'tip2' => AnaYetkiler::MD2_TIP,
                    'kume_id' => $tip,
                    'derinlik' => 5,
                    'selected' => '',
                    'name' => 'kume_id'

                ],
                'sql' => " WHERE '" . $tip_verisi['kume_id'] . "' ",
                'label' => 'Uygulanabilir Üye Grupları',
            ],
```

veri kısmına girilen datalara göre istenilen veriler girildiği halde bize istediğimiz listeyi getirir

###### Hangi Durumlarda Kullanılır

Birden fazla seçim yapılması gerektiren listeler için kullanılır
