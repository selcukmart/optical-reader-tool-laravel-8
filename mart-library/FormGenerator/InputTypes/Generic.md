![Formsection](https://s3.eu-central-1.amazonaws.com/static.testbank.az/uploads/files/15-1618918226-ok-image.png)

###### Bu Sınıf Ne İşe Yarar?

Birbiriyle bağlantılı olan öğeleri listeler.

###### Nasıl Kullanılır

```
   [
        'type' => 'generic',
        'output' => '<div class="yerleske-alani">' . IlIlceSemtMahalle::select($ebeveyn_id = 0, $kume_id = 0, $selected = 0, $yer_prefix = '', $kimlik_bilgisi = $row_table, $INPUT_PREFIX = 'adres_', $YERLESKE_ALANI = '.yerleske-alani') . '</div>',
        'label' => 'İl/İlçe/Sem/Mahalle'
     ]
```

Birbiriyle bağlantılı olan öğeleri select box şeklinde listeler.

###### Hangi Durumlarda Kullanılır

Adres tanımlamalarında kullanılır
