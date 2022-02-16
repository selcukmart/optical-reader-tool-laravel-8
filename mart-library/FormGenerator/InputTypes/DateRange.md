![Currency](https://s3.eu-central-1.amazonaws.com/static.testbank.az/uploads/files/15-1618992978-ok-image.png)

###### Bu Sınıf Ne İşe Yarar?

Başlama ve Bitiş tarihleri belirtildikten sonra sistem tarihleri bu aralıkta açar

###### Nasıl Kullanılır

```
               [
                'type' => 'date_range',
                'label' => 'Sınav Başlama ve Bitiş Tarihleri',
                'help_block' => 'Sistem tarihleri bu aralıkta açacaktır.',
                'attributes' => [
                    'name' => 'baslama_bitis',
                    'class' => 'form-control input-large no-inputmask'
                ]
            ],
           
```

type kısmından date_rage ayarlanır.

###### Hangi Durumlarda Kullanılır

Başlangıç ve bitiş tarihleri ile sınırlandırılması gereken durumlarda kulanılır..
