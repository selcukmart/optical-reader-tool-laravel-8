<?php

/**
 * Kurs ve Spor Okulu Otomasyon Sistemi
 * PHP version 5
 *
 * LICENSE: Henüz lisans yok.
 *
 * @category   Kategori
 * @package    Paket
 * @author     Selcuk MART <admin@hostingdevi.com>
 * @copyright  2006-2016 ONLINEKURUM.COM
 * @license    *
 * @version    SVN: $Id$
 * @link
 * @since      File available since Release 1.0.0
 */
/* Coder: Selçuk MART - 07.03.2012 */
namespace Time;
/*
 * Örnekler
 *
 * $today = date("F j, Y, g:i a");                 : March 10, 2001, 5:16 pm
 * $today = date("m.d.y");                         : 03.10.01
 * $today = date("j, n, Y");                       : 10, 3, 2001
 * $today = date("Ymd");                           : 20010310
 * $today = date('h-i-s, j-m-y, it is w Day');     : 05-16-18, 10-03-01, 1631 1618 6 Satpm01
 * $today = date('\i\t \i\s \t\h\e jS \d\a\y.');   : it is the 10th day.
 * $today = date("D M j G:i:s T Y");               : Sat Mar 10 17:16:18 MST 2001
 * $today = date('H:m:s \m \i\s\ \m\o\n\t\h');     : 17:03:18 m is month
 * $today = date("H:i:s");                         : 17:16:18
 */

class Time
{

    public static $dayofyear = [
        1 => 31,
        2 => 28,
        3 => 31,
        4 => 30,
        5 => 31,
        6 => 30,
        7 => 31,
        8 => 31,
        9 => 30,
        10 => 31,
        11 => 30,
        12 => 31
    ];
    public static $months = [
        1 => 'Ocak',
        2 => 'Şubat',
        3 => 'Mart',
        4 => 'Nisan',
        5 => 'Mayıs',
        6 => 'Haziran',
        7 => 'Temmuz',
        8 => 'Ağustos',
        9 => 'Eylül',
        10 => 'Ekim',
        11 => 'Kasım',
        12 => 'Aralık'
    ];
    public static $days_tr = [
        'Pazartesi',
        'Salı',
        'Çarşamba',
        'Perşembe',
        'Cuma',
        'Cumartesi',
        'Pazar'
    ];
    public static $time_abbreviations = ['j', 'n', 'Y', 'G', 'i', 's'];

    const DEFAULT_TIMEZONE = 'Europe/Istanbul';

    /**
     * Constructor
     */
    public function __construct()
    {

    }

    /**
     * 29.03=>88
     * @link araclar/zaman.php description
     */
    public static function dayofyear($str)
    {
        $explode = explode('.', $str);
        if (isset($explode[1])) {
            $return = 0;
            for ($i = 1; $i <= $explode[1]; $i++) {
                if ($i != $explode[1]) {
                    $return += self::$dayofyear[$i];
                } else {
                    $return += $explode[0];
                }
            }
            return $return;
        }

        return $str;
    }

    public static function time()
    {
        return self::second();
    }

    public static function second()
    {
        date_default_timezone_set(self::DEFAULT_TIMEZONE);
        return time();
    }

    /**
     * Gün farkı bul
     */
    public static function findDaysDiff($time)
    {
        $fark = (self::second() - $time);
        return ($fark - ($fark % 86400)) / 86400;
    }

    /**
     * Gün farkı bul
     */
    public static function findDiffTimeToDays($time1, $time2)
    {
        $fark = ($time1 - $time2);
        return ($fark - ($fark % 86400)) / 86400;
    }

    /**
     * Gün farkı bul
     */
    public static function convertToDays($fark)
    {
        return ($fark - ($fark % 86400)) / 86400;
    }

    public static function convertToMonth($saniye)
    {
        $ay = 30;
        $gun = self::convertToDays($saniye);
        return round($gun / $ay);
    }

    /**
     * get time array
     */
    public static function timeArray()
    {
        date_default_timezone_set(self::DEFAULT_TIMEZONE);
        return getdate();
    }

    /**
     * get day.moon.year
     */
    public static function setdmY()
    {
        date_default_timezone_set(self::DEFAULT_TIMEZONE);
        return date('d.m.Y');
    }

    /**
     * Hour 18:18
     */
    public static function setNowHour()
    {
        date_default_timezone_set(self::DEFAULT_TIMEZONE);
        return date('H:i');
    }

    /**
     * Translate days to turkish
     */
    public static function translateDays($day)
    {
        $en_to_tr = [
            'monday' => 'Pazartesi',
            'tuesday' => 'Salı',
            'wednesday' => 'Çarşamba',
            'thursday' => 'Perşembe',
            'friday' => 'Cuma',
            'saturday' => 'Cumartesi',
            'sunday' => 'Pazar'
        ];
        return $en_to_tr [strtolower($day)];
    }

    /**
     * Translate days to turkish
     */
    public static function translate_days_no_tr_character($day)
    {
        $en_to_tr = [
            'monday' => 'pazartesi',
            'tuesday' => 'sali',
            'wednesday' => 'carsamba',
            'thursday' => 'persembe',
            'friday' => 'cuma',
            'saturday' => 'cumartesi',
            'sunday' => 'pazar'
        ];
        return $en_to_tr [strtolower($day)];
    }

    /**
     * translate months to turkish
     */
    public static function translate_months($month)
    {
        $en_to_tr = [
            'january' => 'Ocak',
            'february' => 'Şubat',
            'march' => 'Mart',
            'april' => 'Nisan',
            'may' => 'Mayıs',
            'june' => 'Haziran',
            'july' => 'Temmuz',
            'august' => 'Ağustos',
            'september' => 'Eylül',
            'october' => 'Ekim',
            'november' => 'Kasım',
            'december' => 'Aralık'
        ];
        return $en_to_tr [strtolower($month)];
    }

    /**
     * 15.03
     */
    public static function set_day_tr($unix_time = NULL)
    {
        if (is_null($unix_time)) {
            $unix_time = self::second();
        }
        if (!self::is_unix($unix_time)) {
            return $unix_time;
        } else {
            date_default_timezone_set(self::DEFAULT_TIMEZONE);
            return date('d.m', $unix_time);
        }
    }

    /**
     * 15.03.2012 20:50 Perşembe
     */
    public static function set_gun_saat($unix_time = NULL)
    {
        if (is_null($unix_time)) {
            $unix_time = self::second();
        }
        if (!self::is_unix($unix_time)) {
            return $unix_time;
        } else {
            date_default_timezone_set(self::DEFAULT_TIMEZONE);
            $weekday = getdate($unix_time);
            return date('d.m.Y H:i', $unix_time) . " " . ucfirst(self::translateDays($weekday ['weekday']));
        }
    }

    public static function date($str)
    {
        date_default_timezone_set(self::DEFAULT_TIMEZONE);
        return date($str);
    }

    /**
     * 15.03.2012 20:50 Persembe
     */
    public static function set_gun_saat_en($unix_time = NULL)
    {
        if (is_null($unix_time)) {
            $unix_time = self::second();
        }
        if (!self::is_unix($unix_time)) {
            return $unix_time;
        } else {
            date_default_timezone_set(self::DEFAULT_TIMEZONE);
            $weekday = getdate($unix_time);
            return date('d.m.Y H:i', $unix_time) . " " . ucfirst(self::translate_days_no_tr_character($weekday ['weekday']));
        }
    }

    private static function is_unix($unix_time)
    {
        return (is_numeric($unix_time) && $unix_time > 0);
    }

    public static function hours_interval_control($unix_time, $start_hour, $finish_hour, $return = '10')
    {
        if (!self::is_unix($unix_time)) {
            return $unix_time;
        } else {
            $hour = self::get_hour($unix_time);
            if ($hour >= $start_hour && $hour <= $finish_hour) {
                return $unix_time;
            } else {
                $set_day = self::set_day($unix_time);
                $day_unix_time = $return * 3600;
                $unix_time = self::make_unix_time($set_day) + $day_unix_time;
                return $unix_time;
            }
        }
    }

    public static function get_hour($unix_time)
    {
        if (!self::is_unix($unix_time)) {
            return $unix_time;
        } else {
            date_default_timezone_set(self::DEFAULT_TIMEZONE);
            return date('H', $unix_time);
        }
    }

    /**
     * 20:50
     */
    public static function set_saat($unix_time = NULL)
    {
        if (is_null($unix_time)) {
            $unix_time = self::second();
        }
        if (!self::is_unix($unix_time)) {
            return $unix_time;
        } else {
            date_default_timezone_set(self::DEFAULT_TIMEZONE);
            return date('H:i', $unix_time);
        }
    }

    /**
     * 15.03.2012 20:50:39 Perşembe
     */
    public static function set_gun_saat_saniye($unix_time = NULL)
    {
        if (is_null($unix_time)) {
            $unix_time = self::second();
        }
        if (!self::is_unix($unix_time)) {
            return $unix_time;
        } else {
            date_default_timezone_set(self::DEFAULT_TIMEZONE);
            $weekday = getdate($unix_time);
            return date('d.m.Y H:i:s', $unix_time) . " " . ucfirst(self::translateDays($weekday ['weekday']));
        }
    }

    /**
     * 02.05.2012__09_51_12__carsamba
     */
    public static function set_gun_saat_saniye_dosya($unix_time = NULL)
    {
        if (is_null($unix_time)) {
            $unix_time = self::second();
        }
        if (!self::is_unix($unix_time)) {
            return false;
        } else {
            date_default_timezone_set(self::DEFAULT_TIMEZONE);
            $weekday = getdate($unix_time);
            $date = date('d.m.Y H:i:s', $unix_time) . " " . self::translate_days_no_tr_character($weekday ['weekday']);
            $exp = explode(" ", $date);
            $bas = $exp [0] . "__";
            $exp2 = explode(":", $exp [1]);
            $imp = implode("_", $exp2);
            $bas .= $imp . "__";
            $bas .= $exp [2];
            return $bas;
        }
    }

    /**
     * 15.03.2012 Perşembe
     */
    public static function set_gun($unix_time = NULL)
    {
        if (is_null($unix_time)) {
            $unix_time = self::second();
        }
        if (!self::is_unix($unix_time)) {
            return $unix_time;
        } else {
            date_default_timezone_set(self::DEFAULT_TIMEZONE);
            $weekday = getdate($unix_time);
            return date('d.m.Y', $unix_time) . " " . ucfirst(self::translateDays($weekday ['weekday']));
        }
    }

    /**
     * 15.03.2012
     */
    public static function set_day($unix_time = NULL, $set_now = false)
    {
        if ($set_now && is_null($unix_time)) {
            $unix_time = self::second();
        }
        if (!self::is_unix($unix_time)) {
            return $unix_time;
        } else {
            date_default_timezone_set(self::DEFAULT_TIMEZONE);
            $tr_format = date('d.m.Y', $unix_time);
            return $tr_format;
        }
    }

    /**
     * 15.03.2012 20:50
     */
    public static function set_day_time($unix_time = NULL)
    {
        if (is_null($unix_time)) {
            $unix_time = self::second();
        }
        if (!self::is_unix($unix_time)) {
            return $unix_time;
        } else {
            date_default_timezone_set(self::DEFAULT_TIMEZONE);
            return date('d.m.Y H:i', $unix_time);
        }
    }

    /**
     * 11/2012
     */
    public static function set_ay_yil($unix_time = NULL)
    {
        if (is_null($unix_time)) {
            $unix_time = self::second();
        }
        if (!self::is_unix($unix_time)) {
            return $unix_time;
        } else {
            date_default_timezone_set(self::DEFAULT_TIMEZONE);
            return date('m / Y', $unix_time);
        }
    }

    /**
     * 2012
     */
    public static function set_yil($unix_time = NULL)
    {
        if (is_null($unix_time)) {
            $unix_time = self::second();
        }
        if (!self::is_unix($unix_time)) {
            return $unix_time;
        } else {
            date_default_timezone_set(self::DEFAULT_TIMEZONE);
            return date('Y', $unix_time);
        }
    }

    /**
     * 20.12.2012 09:00 -> 133021021
     * @example araclar/make_unix_time.php
     */
    public static function make_unix_time($veri)
    {
        if (isset($veri) && is_string($veri) && strlen($veri) > 0 && !is_numeric($veri)) {
            date_default_timezone_set(self::DEFAULT_TIMEZONE);
            while (preg_match("/  /", $veri)) {
                $veri = preg_replace("  ", " ", $veri);
            }
            $veri = trim($veri);
            $x = explode(" ", $veri);
            //$tarih = $x[0];
            if (isset($x[1])) {
                //$saat = $x[1];
                $ex = explode(":", $x[1]);
                for ($i = 0; $i < 3; $i++) {
                    if (!isset($ex[$i])) {
                        $ex[$i] = 0;
                    }
                    $ex[$i] = (int)$ex[$i];
                }
            } else {
                $ex[0] = 0;
                $ex[1] = 0;
                $ex[2] = 0;
            }
            $exp = '';
            if (preg_match("/:/", $x[0])) {
                $ex = explode(":", $x[0]);
                for ($i = 0; $i < 3; $i++) {
                    if (!isset($ex[$i])) {
                        $ex[$i] = 0;
                    }
                    $ex[$i] = (int)$ex[$i];
                }
            } else if (false !== strpos($x[0], ".")) {
                $exp = explode(".", $x[0]);
            }

            if (!is_array($exp)) {
                $exp[0] = 0;
                $exp[1] = 0;
                $exp[2] = 0;
            } else {
                for ($i = 0; $i < 3; $i++) {
                    if (!isset($exp[$i])) {
                        $exp[$i] = 0;
                    }
                    $exp[$i] = (int)$exp[$i];
                }
            }
            if (($exp[0] == 0 && $exp[1] == 0 && $exp[2] == 0) && ($ex[0] > 0 || $ex[1] > 0 || $ex[2] > 0)) {
                return $ex[0] * 3600 + $ex[1] * 60;
            }

            return mktime($ex[0], $ex[1], $ex[2], $exp [1], $exp [0], $exp [2]);
        }

        return $veri;
    }

    public static function hafta($unix)
    {
        $bir = 7 * 24 * 60 * 60;
        return floor($unix / $bir);
    }

    public static function ay($unix)
    {
        $bir = 24 * 60 * 60 * 30;
        return ceil($unix / $bir);
    }

    /**
     *    Dolgusuz Gösterimler
     *    j=>Gün
     *    n=>Ay
     *    Y=>yıl,
     *    G=>saat
     *    i=>dakika
     *    s=>Saniye
     */
    public static function otele($unix, $miktar, $ne)
    {
        settype($miktar, 'integer');
        if (is_numeric($miktar) && in_array($ne, self::$time_abbreviations)) {
            date_default_timezone_set(self::DEFAULT_TIMEZONE);
            $n = date($ne, $unix) + $miktar;
            if ($ne == 'j') {
                return mktime(0, 0, 0, date('n', $unix), $n, date('Y', $unix));
            } elseif ($ne == 'n') {
                return mktime(0, 0, 0, $n, date('j', $unix), date('Y', $unix));
            } elseif ($ne == 'Y') {
                return mktime(0, 0, 0, date('n', $unix), date('j', $unix), $n);
            } elseif ($ne == 'G') {
                return mktime($n, 0, 0, date('n', $unix), date('j', $unix), date('Y', $unix));
            } elseif ($ne == 'i') {
                return mktime(date('H', $unix), $n, 0, date('n', $unix), date('j', $unix), date('Y', $unix));
            } elseif ($ne == 's') {
                return mktime(date('H', $unix), date('i', $unix), $n, date('n', $unix), date('j', $unix), date('Y', $unix));
            }
        }
    }

    /**
     * 20.12.2012 09:00 -> 133021021
     */
    public static function date_time2unix_time_stamp($veri)
    {
        return self::make_unix_time($veri);
    }

    public static function cron_format($zaman = NULL)
    {
        if (is_null($zaman)) {
            $zaman = self::second();
        }
        return date('Y-m-d H:i', $zaman);
    }

    public static function make_time($ay = NULL, $gun = NULL, $yil = NULL, $saat = 0, $dakika = 0, $saniye = 0)
    {
        date_default_timezone_set(self::DEFAULT_TIMEZONE);
        if (is_null($ay)) {
            $ay = date("n");
        }

        if (is_null($gun)) {
            $gun = date("j");
        }

        if (is_null($yil)) {
            $yil = date("Y");
        }
        return mktime($saat, $dakika, $saniye, $ay, $gun, $yil);
    }

    public static function secondsToTime($seconds)
    {
        $dtF = new DateTime("@0");
        $dtT = new DateTime("@$seconds");
        return $dtF->diff($dtT)->format('%a Gün, %h Saat, %i Dakika, %s Saniye');
    }

    public static function konusma_dili($unix_time)
    {
        $bugun = self::make_time(date('n'), date('j'), date('Y'));
        $yarin = self::make_time(date('n'), date('j') + 1, date('Y'));
        $dun = self::make_time(date('n'), date('j') - 1, date('Y'));
        if ($unix_time >= $bugun && $unix_time < $yarin) {
            return 'bugün';
        } elseif ($unix_time >= $dun && $unix_time < $bugun) {
            return 'dün';
        } else {
            return self::set_gun($unix_time);
        }
    }

    public static function stateLabelledDate($date)
    {
        $note = '';
        $label = 'success';
        $now = self::second();
        $one_month = $date - 2592000;
        $two_month = $one_month - 2592000;
        if ($now > $date) {
            $label = 'danger';
            $note = '<div class="alert alert-danger">  Maalesef hizmet süreniz dolmuştur.
<a class="btn btn-success btn-xs" target="__blank" href="https://www.onlinekurum.com/products/">Buraya tıklayarak sipariş verebilirsiniz</a></div>';
        } elseif ($now > $one_month) {
            $kalan_gun = round(($date - $now) / 86400);
            $label = 'warning';
            $note = '<div class="alert alert-warning"> Hizmet süreniz ' . $kalan_gun . ' gün sonra dolacaktır.
<a class="btn btn-success btn-xs" target="__blank" href="https://www.onlinekurum.com/products/">Buraya tıklayarak sipariş verebilirsiniz</a></div>';
        } elseif ($now > $two_month) {
            $label = 'info';
            $note = '<div class="alert alert-info"> Hizmet süreniz dolmak üzeredir.
<a class="btn btn-success btn-xs" target="__blank" href="https://www.onlinekurum.com/products/">Buraya tıklayarak sipariş verebilirsiniz</a></div>';
        }
        return '<span class="label label-' . $label . '">' . self::set_day($date) . '</span>' . $note;
    }

    public function __destruct()
    {
        // TODO: Implement __destruct() method.
    }

}
