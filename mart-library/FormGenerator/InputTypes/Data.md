![query](https://s3.eu-central-1.amazonaws.com/static.testbank.az/uploads/files/15-1619421581-ok-image.png)




###### Bu Sınıf Ne İşe Yarar?

Sabit değeri olan input alanlarının default değerleri belirlenerek formda görünmesini engellemeye yarar

###### Nasıl Kullanılır

![keyvalue](https://s3.eu-central-1.amazonaws.com/static.testbank.az/uploads/files/15-1619421818-ok-image.png)
```
          [
            'type' => 'radio',
            'label' => 'Bileşen Yüklenme Yeri',
            'dependend' => [
                'group' => 'bilesen_turu',
                'dependend' => 'bilesen_turu-dosya'
            ],
            'attributes' => [
                'name' => 'tip5'
            ],
            'options' => [
                'data' => [
                    'from' => 'key_value_array',
                    'key_value_array' => [
                        '0' => 'Header ve Footer ın arasına koy modülü',
                        '1' => 'Modül tek başına yüklensin',
                    ]
                ]
            ]
        ],
                                                               
```

yukarıdaki kod key_value_array kısmında verilen değerlerde 0 option value kısmına tekabül eder. elle değer girmemiz
gerektiği durumlarda key_value_array kullanılır.



![sql](https://s3.eu-central-1.amazonaws.com/static.testbank.az/uploads/files/15-1619422315-ok-image.png)
```
               [
            'type' => 'radio',
            'label' => 'Bileşen Yüklenme Yeri',
            'dependend' => [
                'group' => 'bilesen_turu',
                'dependend' => 'bilesen_turu-dosya'
            ],
            'attributes' => [
                'name' => 'tip5'
            ],
            'options' => [
                'data' => [
                    'from' => 'sql',
                    'sql' => "SELECT 
                                * FROM " . $vtable2 . " AS " . $vtable2_as . " 
                                WHERE 
                                " . $vtable2_as . ".tip='1' 
                                AND " . $vtable2_as . ".ebeveyn_id='1'
                                "
                ]
            ]
        ],
                                          
```

Yukarıdaki Kod ile sql sorgusundan gelen sonuçları listelemeye imkan sağlar.Data kısmında from => sql olarak
belirlendikten sonra sql sorgumuzu belirtiyoruz.

```
            [
            'type' => 'radio',
            'attributes' => [
                'name' => 'yer'
            ],
            'empty_option' => false,
            'options' => [
                'data' => [
                    'from' => 'sql',
                    'sql' => "SELECT * FROM " . \Nesne\Nesne::table() . " WHERE tip='" . \Nesne\Sube::TIP . "' AND durum='1'"
                ]
            ],
            'label' => 'Menü Yeri',
        ],
                                     
               
```

Yukarıdaki kod bloğunda query olan kısma sorgumuz varsa ekleyip parametre olarak gönderiyoruz dönen sonuç sorgunun
cevabını verir.



![rows](https://s3.eu-central-1.amazonaws.com/static.testbank.az/uploads/files/15-1619433063-ok-image.png)

```
            [
                 'type' => 'radio',
                  'attributes' =>[
                 'name' => 'x'
                        ],
        'options' => [
            'rows' => [
                [
                    'id' => 1,
                    'isim' => 'a'
                ],
                [
                    'id' => 2,
                    'isim' => 'b'
                ]
            ]
        ]
    ],
                                    
               
```

//eksik

###### Hangi Durumlarda Kullanılır

select box ihtiyacı olan yerlerde kullanılır.

Önemli Not: Özel input tiplerinin kendi data alma yöntemleri bulunmaktadır.


