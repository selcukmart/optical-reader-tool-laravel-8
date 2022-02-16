###### Bu Sınıf Ne İşe Yarar?
![AdvancedRecursiveForm](https://s3.eu-central-1.amazonaws.com/static.testbank.az/uploads/files/15-1618837271-ok-image.png)


Ebeveyn sahibi verilerin iç içe checkbox oluşturmalarını sağlar. "Mart PHP Framework" bu yapının insert ve update lerini destekler. Stabildir. Bu
sınıf onun kolayca Form Üreteci ile üretilebilmesini sağlamak için vardır.

###### Nasıl Kullanılır

```
[
                'type' => 'advanced_recursive_form',
                // 
                'label' => 'Klasörler',
                /**
                * foreign_key veri2_id ise this_key veri_id dir
                * foreign_key veri_id ise this_key veri2_id dir
                * Ön tanımlı olarak foreign_key veri2_id dir
                */
                'foreign_key'=>'veri_id',
                'veri' => [
                    // veri_id nin tipi
                    'tip' => Nesne::MD_TIP,
                    // veri2_id nin tipi
                    'tip2' => KumeIliskileri::MD_TIP,
                    // gerekli değil ama ilişkili tablonun tipini size söyler
                    // mesela bu 2, kumelerde 2 numaralı tipi ifade eder.
                    'dis_tip' => 2,
                    // İlişkili tablo
                    'rec_table' => KumeIliskileri::table(),
                    // sorguyu daraltır.
                    'ekstra' => " AND tip='2' AND durum='1' ORDER BY sira ASC,id ASC",
                    // anlık veri eklemek için kullanılır
                    'url' => '/kumeler/ekle.php?tip=2',
                    // Veri ekleme butonunda bu yazar.
                    'modal-baslik' => 'Klasör Ekle'
                ]
            ]
```

foreign_key, metadata tablosunda bu tablo ile ilişkili alanın karşısında olan alandır. this_key alanı bu tablo hangisi ile( veri_id mi yoksa veri2_id mi) ilişkili belirlerken foreign_key ise diğer ilişkili tablo alanını temsil eder.

###### Hangi Durumlarda Kullanılır
Diyelimki soru havuzunuz var ve bir soruyu birden fazla test veya sınavla eşlemek istediniz. Veya bir videonuz var ve birden fazla klasör içerisinde bu video. İşte tam bu noktada bu form sizi kurtarır.
