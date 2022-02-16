![MetadataInputText](https://s3.eu-central-1.amazonaws.com/static.testbank.az/uploads/files/15-1619424523-ok-image.png)


![MetadataInputRadio](https://s3.eu-central-1.amazonaws.com/static.testbank.az/uploads/files/15-1619424229-ok-image.png)


![MetadataInputTextarea](https://s3.eu-central-1.amazonaws.com/static.testbank.az/uploads/files/15-1619424970-ok-image.png)

![MetadataInputTextarea](https://s3.eu-central-1.amazonaws.com/static.testbank.az/uploads/files/15-1619425806-ok-image.png)


###### Bu Sınıf Ne İşe Yarar?

Bir inputa ait birden fazla veri varsa yada kullanılacaksa bu sınıf kullanılır.

###### Nasıl Kullanılır

```
                [
                'type' => 'metadata_input',
                'veri' => [
                    'tip' => Nesne::MD_TIP,
                    // JENERİK TİP, EĞER
                    // BİR TABLO YOKSA TİPTE 6 KULLANILIR
                    'tip2' => 6,
                    'put_empty' => TRUE,
                    'type' => 'text',
                    'use_md_field' => 'tutar',
                    'sabit' => 'video_tutari',
                    'attributes' => ['class' => 'form-control input-large']
                ],
                'label' => 'Video Tutarı',
            ],
         
```

Yukarıdaki kod bloklarında görüldüğü üzere veri dizisinin içerisinde inputa ait birçok özellikler tanımlanmıştır.
İpnputun type'ı  text olarak belirtilmiştir.

```
[
'type' => 'metadata_input',
'dependend' => [
'group' => 'tip3',
'dependend' => 'tip3-1'
],
'label' => 'Sınava Kayıt Öncesi Anket Yapılsın mı',
'veri' => [
'tip' => Nesne::MD_TIP,
// Jenerik Kullanım
'tip2' => 6,
'veri_id' => isset($row_table['id']) ? $row_table['id'] : 0,
'type' => 'radio',
'data' => evetArr(),
'data_type' => 'array',
'default_value' => 0,
'attributes' => ['class' => ''],
'sabit' => 'sinav_oncesi_soru_durumu',
]
],

```
Yukarıdaki kod bloklarında görüldüğü üzere veri dizisinin içerisinde inputa ait birçok özellikler tanımlanmıştır.
İpnputun type'ı  radio olarak belirtilmiştir.





```
[
'type' => 'metadata_input',
'dependend' => [
'group' => 'tip3',
'dependend' => 'tip3-1'
],
'label' => 'Sınava Kayıt Öncesi Anket Yapılsın mı',
'veri' => [
'tip' => Nesne::MD_TIP,
// Jenerik Kullanım
'tip2' => 6,
'veri_id' => isset($row_table['id']) ? $row_table['id'] : 0,
'type' => 'textarea',
'data' => evetArr(),
'data_type' => 'array',
'default_value' => 0,
'attributes' => ['class' => ''],
'sabit' => 'sinav_oncesi_soru_durumu',
]
],

```
Yukarıdaki kod bloklarında görüldüğü üzere veri dizisinin içerisinde inputa ait birçok özellikler tanımlanmıştır.
İpnputun type'ı textarea olarak belirtilmiştir.



```
[
'type' => 'metadata_input',
'derece' => 10000,
'veri' => [
'tip' => Nesne::MD_TIP,
'tip2' => Nesne::MD_TIP,
'veri2_tip' => 50,
'more_sql' => " WHERE durum='1' ",
'put_empty' => true,
'type' => 'select',
'attributes' => ['class' => 's2 form-control input-large']
],
'label' => 'Kullanılacak Lisans',
'help_block' => 'Online Ders için'
],
```

Yukarıdaki kod bloklarında görüldüğü üzere veri dizisinin içerisinde inputa ait birçok özellikler tanımlanmıştır.
İpnputun type'ı select olarak belirtilmiştir.




###### Hangi Durumlarda Kullanılır

Bir input ile 1 den fazla veri gönderilecekse yada listelenmek istenen verinin birden fazla belirtilecek özeliği varsa
bu sınıf kullanılır.
