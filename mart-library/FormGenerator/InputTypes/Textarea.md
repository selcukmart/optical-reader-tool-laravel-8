![file](https://s3.eu-central-1.amazonaws.com/static.testbank.az/uploads/files/15-1618904670-ok-image.png)

###### Bu Sınıf Ne İşe Yarar?

Satır sayısı fazla olan metin giriş kontrolü (textarea) oluşturulmasını sağlar.

###### Nasıl Kullanılır

```
            [
                'type' => 'textarea',
                'attributes' => [
                    'name' => 'icerik'
                ],
                'help_block' => 'Sihirli Kelimeler; <br> {{AD}},{{SOYAD}},{{MAIL}},{{TEL}} <br> Mail Konusunun Sonuna Eklenirse klasik alanı ekler: {{STANDART_SUBJECT}} <br> Kapanışa Eklenir: {{STANDART_CIKIS}}',
                'label' => 'Mail İçeriği'
            ],
```

Type kısmından textarea olarak belirlenen inputun attributes kısmından name'i belirlenir.
Başlık ise label'dan belirlenir

###### Hangi Durumlarda Kullanılır

Birden fazla satır girişi olan metin giriş ihtiyacı duyulan yerlerde kullanılır.





