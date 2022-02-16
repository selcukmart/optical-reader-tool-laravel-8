<?php
/**
 * @author selcukmart
 * 27.01.2021
 * 22:37
 */

namespace FormGenerator\Tools;

use UserGuide\UserGuideFormInjector\UserGuideFormInjector;

class Label
{
    private
        $run_user_guide = false,
        $label = '',
        $label_conf,
        $found_label = false,
        $translate = true,
        $label_with_help,
        $label_without_help,
        $already_set_label = true,
        $UserGuideFormInjector;

    private $name2label = [
        'language' => 'dil',
        'base_language' => 'Ana Dil',
        'msgid' => 'Çevirilecek Metin',
        'msgstr' => 'Metnin Çevirisi',
        'msgctxt' => 'Bağlam',
        'icerik' => 'İçerik',
        'ad' => 'Ad',
        'soyad' => 'Soyad',
        'durum' => 'Durum',
        'id' => 'No',
        'tarih' => 'İşlem Tarihi',
        'antrenman_tarihi' => 'Antrenman Tarihi',
        'cep_tel' => 'Cep Telefonu',
        'mail' => 'Mail',
        'calisma_durumu' => 'İş Durumu',
        'ise_baslama_tarihi' => 'İşe Başlama Tarihi',
        'yoklama_id' => 'Yoklama No',
        'anahtar' => 'Anahtar',
        'birinci_deger' => 'Birinci Değer',
        'ikinci_deger' => 'İkinci Değer',
        'katsayi' => 'Katsayı',
        'odenen' => 'Ödenen',
        'gelir_gider_id' => 'Gelir Gider No',
        'yil' => 'Yıl',
        'odeme_secenekleri' => 'Ödeme Seçenekleri',
        'odeme_tarihi' => 'Tahsilat Tarihi',
        'odenme_tarihi' => 'Tahsilat Tarihi',
        'birim_alis_fiyati' => 'Birim Alış Fiyatı',
        'siparis_durumu' => 'Sipariş Durumu',
        'tel' => 'Telefon',
        'tuzel' => 'Tüzel Kişilik',
        'sube_okul_adi' => 'Okul Adı',
        'sube_aidat' => 'Aidat',
        'sube_kapasite' => 'Kapasite',
        'sube_ogrenci_sayisi' => 'Ögrenci Sayısı',
        'islem' => 'İşlem',
        'baslik' => 'Başlık',
        'aciklama' => 'Açıklama',
        'sira' => 'Sıra',
        'ucret' => 'Ücret',
        'kayit_tarihi' => 'Kayıt Tarihi',
        'on_kayit_tarihi' => 'Ön Kayıt Tarihi',
        'iptal_edilecek_tarih' => 'İptal Tarihi',
        'dondurulacak_tarih' => 'Dondurma Tarihi',
        'aktiflesme_tarihi' => 'Aktifleşme Tarihi',
        'sozlesme_bitis_tarihi' => 'Sözleşme Bitiş Tarihi',
        'antrenman_tarihi_NOT_IN_SQL' => 'Antrenman Tarihi',
        'dogum_tarihi' => 'Doğum Tarihi',
        'dogru_sayisi' => 'Doğru Sayısı',
        'yanlis_sayisi' => 'Yanlış Sayısı',
        'baslama_tarihi' => 'Başlama Tarihi',
        'bitis_tarihi' => 'Bitiş Tarihi',
        'bos_sayisi' => 'Boş Sayısı',
        'toplam_birim_nesne_sayisi' => 'Toplam Soru Sayısı',
        'tc_kimlik_no' => 'Vatandaşlık Numarası'
    ];

    public function __construct(array $label_conf)
    {

        if (isset($label_conf['translate'])) {
            $this->translate = $label_conf['translate'];
        }

        $this->label_conf = $label_conf;

        if (isset($this->label_conf['label']) && is_string($this->label_conf['label']) && !empty($this->label_conf['label'])) {
            $this->label = $this->label_conf['label'];

            $this->setFoundLabel(true);

            return;
        }

        if (!$this->isFoundLabel()) {
            $this->detectLabel();

            if (!$this->isFoundLabel()) {
                if (isset($this->label_conf['label']) && is_array($this->label_conf['label']) && isset($this->label_conf['label']['callback']) && is_callable($this->label_conf['label']['callback'])) {
                    $this->processCallback();
                }
            }
        }
    }

    private function setUserGuideInjector()
    {

        $tip = 0;

        if (isset($_GET['tip'])) {
            $tip = (int)$_GET['tip'];
        }

        $this->UserGuideFormInjector = UserGuideFormInjector::getInstance($this->label_conf, $tip);
    }

    public function detectLabel()
    {
        $has_label = isset($this->label_conf['attributes']['name'], $this->name2label[$this->label_conf['attributes']['name']]);

        if ($has_label) {
            $this->setFoundLabel(true);
            $this->label = $this->name2label[$this->label_conf['attributes']['name']];
            return $this->label;
        }

        if (!isset($this->label_conf['attributes']['name'])) {
            return false;
        }

        $x = explode('_', $this->label_conf['attributes']['name']);

        foreach ($x as $index => $item) {

            $this->label .= mb_ucfirst($item, 'UTF-8') . ' ';
        }

        $this->label = trim($this->label);

        if (is_string($this->label) && !empty($this->label)) {
            $this->setFoundLabel(true);
        }

        return $this->label;
    }

    public function processCallback()
    {
        $this->label = call_user_func_array($this->label_conf['label']['callback'], [$this->label_conf['label']]);

        if (is_string($this->label) && !empty($this->label)) {
            $this->setFoundLabel(true);
        }
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        if ($this->isFoundLabel()) {
            if ($this->translate) {
                $return = ___($this->label);
            } else {
                $return = $this->label;
            }

        } else {
            if (isset($this->label_conf['attributes']['name'])) {
                if ($this->translate) {
                    $return = ___($this->label_conf['attributes']['name']);
                } else {
                    $return = $this->label_conf['attributes']['name'];
                }

            } else {
                if (!$this->already_set_label) {
                    $return = '';
                } else {
                    if ($this->translate) {
                        $return = ___('Başlıksız');
                    } else {
                        $return = 'Başlıksız';
                    }
                }
            }
        }

        $this->label_without_help = $return;

        if(!$this->run_user_guide){
            $this->label_with_help = $this->label_without_help;
        }else{
            $this->label_with_help = $this->UserGuideFormInjector->renderHelp('label', $return);
        }


        return $this->label_with_help;
    }

    /**
     * @return mixed
     */
    public function getUserGuideFormInjector(): UserGuideFormInjector
    {
        return $this->UserGuideFormInjector;
    }


    /**
     * @return mixed
     */
    public function getLabelWithoutHelp()
    {
        return $this->label_without_help;
    }

    /**
     * @param bool $translate
     */
    public function setTranslate(bool $translate): void
    {
        $this->translate = $translate;
    }

    /**
     * @param bool $found_label
     */
    private function setFoundLabel(bool $found_label): void
    {
        $this->found_label = $found_label;
    }

    /**
     * @return bool
     */
    public function isFoundLabel(): bool
    {
        return $this->found_label;
    }

    /**
     * @return mixed
     */
    public function getLabelWithHelp()
    {
        return $this->label_with_help;
    }

    /**
     * @param bool $already_set_label
     */
    public function setAlreadySetLabel(bool $already_set_label): void
    {
        $this->already_set_label = $already_set_label;
    }

    public function __destruct()
    {

    }
}
