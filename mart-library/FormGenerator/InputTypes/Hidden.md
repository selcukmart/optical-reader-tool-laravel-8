

###### Bu Sınıf Ne İşe Yarar?

Sabit değeri olan input alanlarının default değerleri belirlenerek formda görünmesini engellemeye yarar

###### Nasıl Kullanılır

```
                [
                    'type' => 'hidden',
                    'dependency' => true,
                    'attributes' => [
                        'name' => 'tip2'
                    ],
                    'default_value'=>1,
                ]
```

Yukarıdaki kod bloğunda name'i tip2 olan alanın değeri 1 dir ama formda görünmez . post edilirse 1 değeri gider.

###### Hangi Durumlarda Kullanılır

Arkaplanda göndermek istenilmeyen değerleri göndermeye yarar.
