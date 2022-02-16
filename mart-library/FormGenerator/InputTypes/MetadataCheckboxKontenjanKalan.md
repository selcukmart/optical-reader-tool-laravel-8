![Formsection](https://s3.eu-central-1.amazonaws.com/static.testbank.az/uploads/files/15-1618918226-ok-image.png)

###### Bu Sınıf Ne İşe Yarar?

Birbiriyle bağlantılı olan öğeleri listeler.

###### Nasıl Kullanılır

```
  [
                    'type' => 'metadata_checkbox_kontenjan_kalan',
                    'dependend' => [
                        'group' => 'tip3',
                        'dependend' => 'tip3-0'
                    ],
                    'veri' => [
                        'tip' => Nesne::MD_TIP,
                        'tip2' => Nesne::MD_TIP,
                    ],
                    'sql' => " WHERE xyz.tip='32' ",
                    'dis_tip' => 32,
                    'label' => 'Yer',

                ],
```

Birbiriyle bağlantılı olan öğeleri select box şeklinde listeler.

###### Hangi Durumlarda Kullanılır

Adres tanımlamalarında kullanılır
