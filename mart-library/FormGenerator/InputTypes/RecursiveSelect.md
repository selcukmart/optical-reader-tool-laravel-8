![file](https://s3.eu-central-1.amazonaws.com/static.testbank.az/uploads/files/15-1619428552-ok-image.png)

###### Bu Sınıf Ne İşe Yarar?

Kod parçacığında görüldüğü gibi veri dizisinde belirtilen değerlere göre verileri sıralamayı sağlar.



###### Nasıl Kullanılır

```
            [
                'type' => 'recursive_select',
                'attributes' => [
                    'name' => 'kume_id',
                    'required'
                ],
                'veri' => [
                    'table'=>\KumeIliskileri::table(),
                    'id'=>'id',
                    'ebeveyn_id'=>'ebeveyn_id',
                    'baslik'=>'isim',
                    'ekstra'=>" AND tip='".$tip_verisi['kume_id']."' AND durum='1' ORDER BY sira ASC,id ASC",
                    'derinlik'=>5,
                    'selected'=>''
                    
                ],
                'label' => 'Kategori',
            ]
```

Tüm input öğelerinde olduğu gibi, kullanılabilir olması için attributes alanında bir name tanımlamanız gerekir.
Kodun içerisinde 'veri' dizisinde tablo ,veritabanı sorgusu id ve istediğimiz attribute'leri tanımlayabiliriz.



###### Hangi Durumlarda Kullanılır
Veritabanından istenilen verileri çekip listelemeye ihtiyac duyulduğu zaman bu sınıf kullanılır.

Derinlik nedir?
Veri dizisinde bulunan özyinelemeli (Recursive) işlem sayısını ifade eder.Diğer bir anlatımla max kaç adet alt veri sınırlaması yapılır. 





