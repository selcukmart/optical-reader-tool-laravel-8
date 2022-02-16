



###### Bu Sınıf Ne İşe Yarar?

Seçildiğinde seçilen seçeneğe bağlı elementleri veya değerlerin ekranda gösterilmesini sağlar.


###### Nasıl Kullanılır

###### Dependency : 
Burada belirtilen 2 seçenek buna bağımlı elementlerin neler olacağını belirler. 2 adet değer var.(dosya ve içerik)
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

![Dependency1](https://s3.eu-central-1.amazonaws.com/static.testbank.az/uploads/files/15-1619097919-ok-image.png)

_______________________________________________________________________________________________________________________


###### Dependend : 
Burada belirtilen dependend dizisi üstteki dependency true olan elementin değerine bağlıdır. Aşağıdaki kodda görüldüğü üzere
bilesen_turu-dosya (dosya değerine bağlı). Eğer dosya değeri seçilirse aşağıdaki elementler aktif olacak.

  ```      
         [
            'type' => 'radio',
            'label' => 'Bileşen Yüklenme Yeri',
            'dependend' => [
                'group' => 'bilesen_turu',
                'dependend' => 'bilesen_turu-dosya'
            ],
            'attributes' => [
                'name' => 'tip5'
            ],
            'options' => [
                'data' => [
                    'from' => 'key_value_array',
                    'key_value_array' => [
                        '0' => 'Header ve Footer ın arasına koy modülü',
                        '1' => 'Modül tek başına yüklensin',
                    ]
                ]
            ]
        ],
        
```
![Dependency1](https://s3.eu-central-1.amazonaws.com/static.testbank.az/uploads/files/15-1619098045-ok-image.png)
     


İlk başta seçilen değerin dependency değerini true yapılır. Daha sonra o değerin name değerine göre ona bağımlı 
olan elementlere dependend dizisi eklenir ve hangi değerine baglı ile yukarıdaki kodda görüldüğü üzere 
seçilen değerin value si belirtilir.

###### Hangi Durumlarda Kullanılır

Resimde görüldüğü üzere Modül seçildiğinde ; Header ve Footer ın arkasına koy ve Modül tek başına yüklensin seçenekleri gelmiştir.
Yazı alanı Id si seçildiğinde altta input alanı gelmiştir ona bağımlı olarak.
