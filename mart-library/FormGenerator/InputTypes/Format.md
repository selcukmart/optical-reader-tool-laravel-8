###### Bu Sınıf Ne İşe Yarar?

Kullanılacak Bootstrap sürüm versiyonunu belirtmek için kulanılır.

###### Nasıl Kullanılır

##### Form Wizard kullanımı:

Formun wizard şekilde ilerlemeli olunması istenirse format kısmından Bootstrapv3FormWizard olarak ayarlanır.
Aynı zamanda url kısmına &export_format=form-wizard eklendiğinde düz form olan yapı wizard yapısına dönüşür.
```
 'export' => [
        'format' => 'Bootstrapv3FormWizard',
              ],
```
![BootstrapFormWizard](https://s3.eu-central-1.amazonaws.com/static.testbank.az/uploads/files/15-1619427114-ok-image.png)



##### Normal Form kullanımı:
Formun normal düz form olunmasını istenirse format kısmından Bootstrapv3Form olarak ayarlanır.
Aynı zamanda url kısmına &export_format=form eklendiğinde form wizard olan yapı normal form yapısına dönüşür


```
    'export' => [
            'format' => 'Bootstrapv3Form'
                ],
```

![Bootstrapv3Form](https://s3.eu-central-1.amazonaws.com/static.testbank.az/uploads/files/15-1619427214-ok-image.png)




###### Hangi Durumlarda Kullanılır
ScreenShot'larda göründüğü üzere ilk resimde aynı formun wizard şekli. 2. resimde ise düz form şekli ayarlanmıştır.
Formumuzu doldururken section lara göre belirli gruplara göre doldurulması gerektiğinde wizard seçilir. Hepsi aynı
formda olunması istendiğinde 2. seçenek seçilir.
Bootstrap sürüm versiyonu form generator oluşturmak için kullanılan ilk dizidir.
Buradan oluşturacağımı formun sürümünü
belirtmek gerekir. Bu format class'ıyla belirtilir.




