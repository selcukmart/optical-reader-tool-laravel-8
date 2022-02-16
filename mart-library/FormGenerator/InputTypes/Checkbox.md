![Checkbox1](https://s3.eu-central-1.amazonaws.com/static.testbank.az/uploads/files/15-1619012948-ok-image.png)

###### Bu Sınıf Ne İşe Yarar?

Checkbox kullanıcıya seçenekler sunarak bunlardan birini, birkaçını ya da tamamını seçebilmesi istenen durumlar için
kullanılır. Checked özelliği sayesinde seçili olup olmadığının kontrolünü yapabiliriz.

###### Nasıl Kullanılır

```
            [
                'type' => 'checkbox',
                'attributes' => [
                    'name' => 'ebeveyn_id'
                ],
                'option_settings' => [
                    'key' => 'id',
                    'label' => 'isim',

                ],
                'options' => [
                    'data' => [
                        'from' => 'sql',
                        'sql' => "SELECT * FROM " . $vtable3
                    ],
                    'control' => [
                        'sql' => "SELECT * FROM " . \Metadata\Metadata::table() . " WHERE tip= '1' AND tip2='3' ",
                        'parameters' => [
                            'this_field' => 'id',
                            'foreign_field' => 'ebeveyn_id'
                        ]
                    ]
                ],
                'label' => 'Ebeveyn'
            ]
```

Seçilen değerler ebeveyn_id dizisine aktarılır. Attributes kısmından input un name veya diğer özellikleri belirtilir.
Burada 2 tablo kulanılıyor. sql tablosu ve foreign tablo. Options da sorgusu yapılan sql tablosu control kısmındaki this_field oluyor.
control dizisindeki sql sorgusu foreign_field ile eşleşiyor.

###### Hangi Durumlarda Kullanılır

Mesela ilgi alanlarınızı sorduğumuzda bir yada daha fazla ilgi alanınız olabilir bunun gibi çoklu seçim yaptırmak
istediğimiz yerlerde checkbox nesnesini kullanırız.
