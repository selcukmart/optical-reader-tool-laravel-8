###### Bu Sınıf Ne İşe Yarar?

Bu sınıfta filtreleme yapılır. Url ye girilen bilgilere göre istenen inputlar getirilir.

###### Nasıl Kullanılır

##### input-filter kullanımı:

```
'ekle.php?duzenle={ID}&input-filter=input-sifre,input-sifre_tekrar&export_format=form'
```

yukarıdaki url girilen bilgilere gör 2 adet input ekrana gelir. Bunlar ; şifre, şifre tekrar.

![BootstrapFormWizard](https://s3.eu-central-1.amazonaws.com/static.testbank.az/uploads/files/15-1619437714-ok-image.png)

##### input-group-filter kullanımı:

``` 
 'link' => 'ekle.php?duzenle={ID}&input-group-filter=special-settings&export_format=form'
```

Burada ise formda verilen section lara ulaşmak amaçlı kullanılır. yukarıda url e girilen bilgilere göre 1 adet section (
sprecial-settings) form ekranına çağrılır.

![Bootstrapv3Form](https://s3.eu-central-1.amazonaws.com/static.testbank.az/uploads/files/15-1619437999-ok-image.png)

###### Hangi Durumlarda Kullanılır

Formda sadece ihtiyaç duyulan alanlar url den bu sınıfı kullanarak çağırılır. Lazım olan kısımları filtrelemek için
oldukça yararlı bir sınıftır.




