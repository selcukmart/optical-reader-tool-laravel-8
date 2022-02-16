<?php

/**
 * YDS.net Portal
 *
 *
 * Açıklama Gelecek
 *
 * PHP version 5
 *
 * LICENSE: Henüz lisans yok.
 *
 * @category   Kategori
 * @package    Paket
 * @author     Selcuk MART <admin@hostingdevi.com>
 * @copyright  2009-2014 Selçuk MART
 * @license    *
 * @version    SVN: $Id$
 * @link
 * @since      File available since Release 1.0.0
 */

namespace HTML;

use Time\Time;

class Template
{

    /**
     * @var BASIC_FORMAT;
     * @var FORMAT;
     * @var REGEX;
     * @var ADVANCED_FORMAT;
     * @var ADVANCED_FORMAT_LC;
     * @var ADVANCED_REGEX;
     */
    const FORMAT = '{row.field}';
    const REGEX = '/\{.*?\}/';
    const ADVANCED_REGEX = '/\{\{.*?\}\}/';
    const SMARTY_REGEX = '/\{\$.*?\}/';
    const E_REGEX = '/\[\$.*?\]/';
    const USE_CACHE = false;

    /**
     * @param array $defaults
     */
    public static $defaults = [
        'set_cache' => true,
        'use' => 'b',
        'clean_no_longer' => true
    ];

    public function __construct()
    {

    }


    public static function smarty(array $veri, $template, $clean_no_longer = true)
    {
        return self::embed($veri, $template, [
            'use' => 'smarty',
            'clean_no_longer' => $clean_no_longer,
            'lowercase' => true
        ]);
    }

    /**
     * Gelişmiş template yaklaşımı
     * Sadece değişken yerleşimi
     * @param array $veri
     * @param string $template
     * @param array $config
     * @return string
     */
    public static function embed(array $veri, $template, array $config = [])
    {
        $config = set_defaults($config, self::$defaults);
        extract($config);
        return self::html($veri, $template, $set_cache, $use, $config);
    }

    /**
     * Temel template yaklaşımı
     * Sadece değişken yerleşimi
     * @param array $veri
     * @param string $template
     * @param boolean $set_cache
     * @param string $use
     * @param array $config
     * @return string $template
     * @accesse public
     */
    public static function html(array $veri, $template, $set_cache = true, $use = 'b', array $config = [])
    {
        if (!isset($config['__is_def']) || (isset($config['__is_def']) && !$config['__is_def'])) {
            $config = set_defaults($config, self::$defaults);
        }
        if (isset($veri['sql_object'])) {
            $set_cache = false;
        }

        global $cache;

        if ($cache && $set_cache) {
            $md5 = md5(serialize(func_get_args()));
            $cache_key = __FUNCTION__ . $md5;
            if ($cache && $cache->is_set($cache_key)) {
                return $cache->get($cache_key);
            }
        }
        $template = self::xhtml($veri, $template, $xkey = '', $use);
        if ($config['clean_no_longer']) {
            //c($template);
            $template = self::turn($template, $use);
        }
        if ($cache && $set_cache) {
            $cache->set($cache_key, $template);
        }
        //c($template);
        return $template;
    }

    /**
     * Temel template yaklaşımı
     * Sadece değişken yerleşimi
     * @param array $veri
     * @param string $template
     * @param string $xkey
     * @param string $use
     * @return string $template
     */
    public static function xhtml($veri, $template, $xkey = '', $use = 'b', $lowercase = false)
    {
        foreach ($veri as $key => $value) {
            if ($value === 'NULL') {
                continue;
            }
            if (!empty($xkey)) {
                $key = $xkey . '.' . $key;
            }
            if (!is_array($value) && !is_object($value)) {

                $method = $use . '_replace';
                if (method_exists('\Sayfa\Template', $method)) {
                    $template = call_user_func_array(['self', $method], [$key, $value, $template, $lowercase]);
                }
            } else {
                $template = self::xhtml($value, $template, $key, $use);
            }
        }
//        c($template);
        //exit;
        return $template;
    }

    /**
     * SALES TEMPLATE_DIR
     * @param string $template_name
     * @param array $veri
     */
    public static function set($template_name, array $veri)
    {
        if (defined('TEMPLATES_DIR')) {
            $temp = TEMPLATES_DIR . '/' . $template_name . '.php';
        } elseif (defined('TEMPLATE_DIR')) {
            $temp = TEMPLATE_DIR . '/' . $template_name . '.php';
        } elseif (defined('HTML_TEMPLATE_DIR')) {
            $temp = HTML_TEMPLATE_DIR . '/' . $template_name . '.php';
        }
        if (isset($temp) && file_exists($temp)) {
            $template = file_get_contents($temp);
            return self::html($veri, $template);
        }

        return false;
    }

    /**
     * Temel template yaklaşımı
     * Sadece değişken yerleşimi
     * @param array $veri
     * @param string $template
     * @return string $sonuc
     */
    public static function degistir(array $veri, $template)
    {
        global $cache;
        $cache_key = __FUNCTION__ . md5(serialize(func_get_args()));
        if ($cache->is_set($cache_key)) {
            return $cache->get($cache_key);
        }
        foreach ($veri as $key => $value) {
            if (!is_array($value)) {
                $template = self::c_replace($key, $value, $template);
            }
        }
        $sonuc = self::turn($template);
        $cache->set($cache_key, $sonuc);
        return $sonuc;
    }

    /**
     * @param string $template
     * @param string $use
     * @return string $sonuc
     */
    public static function turn($template, $use = 'b')
    {
        global $cache;
        $cache_key = __FUNCTION__ . md5(serialize(func_get_args()));
        if (self::USE_CACHE && $cache && $cache->is_set($cache_key)) {
            return $cache->get($cache_key);
        }
        if ($use === 'b') {
            $sonuc = preg_replace(self::REGEX, '', $template);
        } elseif ($use === 'd') {
            $sonuc = preg_replace(self::ADVANCED_REGEX, '', $template);
        } elseif ($use === 'smarty') {
            $sonuc = preg_replace(self::SMARTY_REGEX, '', $template);
        } elseif ($use === 'e') {
            $sonuc = preg_replace(self::E_REGEX, '', $template);
        }

        if (self::USE_CACHE) {
            $cache->set($cache_key, $sonuc);
        }
        return $sonuc;
    }


    public static function embed_d(array $veri, $template)
    {
        return self::embed($veri, $template, ['use' => 'd']);
    }

    public static function smarty_replace($key, $value, $template)
    {
        global $cache;
        $cache_key = __FUNCTION__ . md5(serialize(func_get_args()));
        if (self::USE_CACHE && $cache && $cache->is_set($cache_key)) {
            return $cache->get($cache_key);
        }

        if (is_string($key) && (is_string($value) || is_numeric($value)) && is_string($template)) {
            $sonuc = mb_str_replace('{$' . $key . '}', $value, $template);
        } else {
            $sonuc = $template;
        }
        if (self::USE_CACHE) {
            $cache->set($cache_key, $sonuc);
        }
        return $sonuc;
    }

    private static function replace_core($key, $value, $template, $use)
    {
        if (is_numeric($value) || is_string($value) || is_bool($value)) {
            //c(func_get_args());
            if (false !== stripos($key, "TIME")) {
                $value = Time::set_day_time($value);
            }
            if ($use === 'b') {
                $template = self::b_replace($key, $value, $template);
            } elseif ($use === 'c') {
                $template = self::c_replace($key, $value, $template);
            } elseif ($use === 'd') {
                $template = self::d_replace($key, $value, $template);
            } elseif ($use === 'e') {
                $template = self::e_replace($key, $value, $template);
            }
        } else {
            $value = (array)$value;
            foreach ($value as $k => $v) {
                $template = self::replace_core($k, $v, $template, $use);
            }
        }
        return $template;
    }

    /**
     * Genel Template Yaklaşımı
     * Sadece değişken yerleşimi
     * @param array $veri
     * @param string $template
     * @return string @sonuc
     */
    public static function h(array $veri, $template)
    {
        global $cache;
        $cache_key = __FUNCTION__ . md5(serialize(func_get_args()));
        if ($cache->is_set($cache_key)) {
            return $cache->get($cache_key);
        }
        foreach ($veri as $key => $value) {
            if (is_array($value) && count($value) > 0) {
                foreach ($value as $k => $v) {
                    if (!is_array($v)) {
                        $template = self::replace($key, $k, $v, $template);
                    }
                }
            } else {
                $template = self::b_replace($key, $value, $template);
            }
        }
        $sonuc = self::turn($template);
        $cache->set($cache_key, $sonuc);
        return $sonuc;
    }

    /**
     * @param array $value
     * @param array $row_table
     * @return boolean $devam
     */
    public static function iff(array $value, array $row_table)
    {
        $devam = false;
        if (!isset($value['if'])) {
            $devam = true;
        }
        if (isset($value['if'])) {
            $devam_arr = [];
            $xx = explode('&', $value['if']);
            $yetki_arr = [
                'derece',
                'ana_yetki_id'
            ];
            foreach ($xx as $val) {
                $y = self::x_explode($val);
                $x = $y[0];
                $if_icerik_alani = false;
                if (isset($x[1])) {
                    $operator = $y[1];

                    $field = $x[0];
                    $deger = $x[1];
                    $xyz = explode('|', $deger);
                    if (in_array($field, $yetki_arr) && isset($_SESSION['giris'][$field])) {
                        $kontrol_degeri = $_SESSION['giris'][$field];
                    } else {
                        if (isset($row_table[$field])) {
                            $kontrol_degeri = $row_table[$field];
                        } else {
                            $kontrol_degeri = false;
                        }
                    }
                    if ($operator === '=' || $operator === '==' || $operator === '===') {
                        $if_icerik_alani = ($kontrol_degeri == $deger && (_sizeof($xyz) == 0 || self::multiple_control_or_s($operator, $kontrol_degeri, $xyz)));
                    } elseif ($operator === '>') {
                        $if_icerik_alani = ($kontrol_degeri > $deger && (_sizeof($xyz) == 0 || self::multiple_control_or_s($operator, $kontrol_degeri, $xyz)));
                    } elseif ($operator === '<') {
                        $if_icerik_alani = ($kontrol_degeri < $deger && (_sizeof($xyz) == 0 || self::multiple_control_or_s($operator, $kontrol_degeri, $xyz)));
                    } elseif ($operator === '<=') {
                        $if_icerik_alani = ($kontrol_degeri <= $deger && (_sizeof($xyz) == 0 || self::multiple_control_or_s($operator, $kontrol_degeri, $xyz)));
                    } elseif ($operator === '>=') {
                        $if_icerik_alani = ($kontrol_degeri >= $deger && (_sizeof($xyz) == 0 || self::multiple_control_or_s($operator, $kontrol_degeri, $xyz)));
                    } elseif ($operator === '!=' || $operator === '!==' || $operator === '<>') {
                        $if_icerik_alani = ($kontrol_degeri != $deger && (_sizeof($xyz) == 0 || self::multiple_control_or_s($operator, $kontrol_degeri, $xyz)));
                    }
                }
                if ($if_icerik_alani) {
                    $devam_arr[] = 1;
                } else {
                    $devam_arr[] = 0;
                }
            }
            if (!in_array(0, $devam_arr)) {
                $devam = true;
            }
        }
        return $devam;
    }

    public static function multiple_control_or_s($operator, $kontrol_degeri, $xyz)
    {
        $return = false;
        if ($operator == '=' || $operator == '==' || $operator == '===') {
            $return = isset($xyz) && in_array($kontrol_degeri, $xyz);
        } elseif ($operator == '>') {
            foreach ($xyz as $key => $value) {
                $return = ($kontrol_degeri > $value);
                if ($return) {
                    break;
                }
            }
        } elseif ($operator == '<') {
            foreach ($xyz as $key => $value) {
                $return = ($kontrol_degeri < $value);
                if ($return) {
                    break;
                }
            }
        } elseif ($operator == '<=') {
            foreach ($xyz as $key => $value) {
                $return = ($kontrol_degeri <= $value);
                if ($return) {
                    break;
                }
            }
        } elseif ($operator == '>=') {
            foreach ($xyz as $key => $value) {
                $return = ($kontrol_degeri >= $value);
                if ($return) {
                    break;
                }
            }
        } elseif ($operator == '!=' || $operator == '!==' || $operator == '<>') {
            foreach ($xyz as $key => $value) {
                $return = ($kontrol_degeri != $value);
                if ($return) {
                    break;
                }
            }
        }

        return $return;
    }

    public static function x_explode($val)
    {
        $arr = [
            '===', '!==', '==', '<=', '>=', '!=', '<>', '=', '<', '>',
        ];
        $x = [];
        $value = '=';
        foreach ($arr as $key => $value) {
            $x = explode($value, $val);
            if (isset($x[1])) {
                break;
            }
        }
        return [$x, $value];
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param string $template
     * @return $sonuc
     */
    public static function b_replace($key, $value, $template)
    {
        global $cache;
        $cache_key = __FUNCTION__ . md5(serialize(func_get_args()));
        if ($cache && $cache->is_set($cache_key)) {
            return $cache->get($cache_key);
        }
        if (is_string($key) && (is_string($value) || is_numeric($value)) && is_string($template)) {
            $sonuc = str_replace('{' . strtoupper($key) . '}', $value, $template);
        } else {
            $sonuc = $template;
        }

        if ($cache) {
            $cache->set($cache_key, $sonuc);
        }
        return $sonuc;
    }

    /**
     * @param string $key
     * @param mixed @value
     * @param string $template
     * @return $sonuc
     */
    public static function c_replace($key, $value, $template)
    {
        global $cache;
        $cache_key = __FUNCTION__ . md5(serialize(func_get_args()));
        if ($cache->is_set($cache_key)) {
            return $cache->get($cache_key);
        }
        if (is_string($key) && (is_string($value) || is_numeric($value)) && is_string($template)) {
            $sonuc = str_replace($key, $value, $template);
        } else {
            $sonuc = $template;
        }
        $cache->set($cache_key, $sonuc);
        return $sonuc;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param string $template
     * @return $sonuc
     */
    public static function d_replace($key, $value, $template)
    {
        global $cache;
        $cache_key = __FUNCTION__ . md5(serialize(func_get_args()));
        if ($cache->is_set($cache_key)) {
            return $cache->get($cache_key);
        }
        if (is_string($key) && (is_string($value) || is_numeric($value)) && is_string($template)) {
            //c($key);
            $sonuc = str_replace('{{' . strtoupper($key) . '}}', $value, $template);
        } else {
            $sonuc = $template;
        }
        $cache->set($cache_key, $sonuc);
        return $sonuc;
    }

    /**
     * @param string $key
     * @param string $k
     * @param mixed $v
     * @param string $template
     * @return $sonuc
     */
    public static function e_replace($key, $value, $template)
    {
        global $cache;
        $cache_key = __FUNCTION__ . md5(serialize(func_get_args()));
        if ($cache->is_set($cache_key)) {
            return $cache->get($cache_key);
        }
        if (is_string($key) && (is_string($value) || is_numeric($value)) && is_string($template)) {
            //c($key);
            $sonuc = str_replace('[' . strtoupper($key) . ']', $value, $template);
        } else {
            $sonuc = $template;
        }
        $cache->set($cache_key, $sonuc);
        return $sonuc;
    }

    public static function replace($key, $k, $v, $template)
    {
        global $cache;
        $cache_key = __FUNCTION__ . md5(serialize(func_get_args()));
        if ($cache->is_set($cache_key)) {
            return $cache->get($cache_key);
        }
        if (is_string($key) && is_string($k) && (is_string($v) || is_numeric($v)) && is_string($template)) {
            $sonuc = str_replace('{' . $key . '.' . $k . '}', $v, $template);
        } else {
            $sonuc = $template;
        }
        $cache->set($cache_key, $sonuc);
        return $sonuc;
    }

    public function __destruct()
    {

    }

}
