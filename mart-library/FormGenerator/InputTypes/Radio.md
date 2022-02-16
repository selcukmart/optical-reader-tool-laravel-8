![file](https://s3.eu-central-1.amazonaws.com/static.testbank.az/uploads/files/15-1618900647-ok-image.png)

###### Bu Sınıf Ne İşe Yarar?

Kullanıcınıza iki veya daha fazla seçenek arasında bir seçim yaptırmak istediğinizde radyo düğmeleri kullanılmalıdır. 
Onay kutularına çok benziyorlar, ancak bir grup seçenek içinde sıfır veya birkaç seçime izin vermek yerine, bir radyo düğmesi sizi yalnızca birini seçmeye zorlar.
En basit haliyle, bir radyo düğmesi, aşağıdaki gibi type özelliği radio olarak ayarlanmış bir giriş öğesidir:


###### Nasıl Kullanılır

```
[
            'type' => 'radio',
            'label' => 'Bileşen Türü',
            'dependency' => true,
            'attributes' => [
                'name' => 'bilesen_turu'
            ],
            'options' => [
                'data' => [
                    'from' => 'key_value_array',
                    'key_value_array' => [
                        'dosya' => 'Modül',
                        'icerik' => 'Yazı Alanı IDsi',
                    ]
                ]
            ]
        ],
```

Tüm input öğelerinde olduğu gibi, kullanılabilir olması için attributes alanında bir name tanımlamanız gerekir 
– bir ad olmadan, form işlenmek üzere bir sunucuya geri gönderildiğinde öğe tanımlanamaz.
Ayrıca bir değer belirlemek istiyorsunuz 
– bu, radio düğmesi seçildiyse sunucuya gönderilen değer olacaktır


###### Hangi Durumlarda Kullanılır
Dosya Sadece 1 seçim yapmamızı gerektiren alanlarda kullanılır.





