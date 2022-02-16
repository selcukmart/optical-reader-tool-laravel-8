<?php

/**
 *
 * Merkezi form sınıfı.
 *
 * Bu sınıf text,textarea vb gibi inputları belirli standartala göre üretir
 * ve formalar için belirlenen davranışları göstermesini sağlar
 *
 * PHP version 5
 *
 * LICENSE: Henüz lisans yok.
 *
 * @category   Sistem Yönetimi
 * @package    Sayfa Paketi
 * @author     Selcuk MART <admin@hostingdevi.com>
 * @copyright  2006-2016 ONLINEKURUM.COM
 * @license    *
 * @version    SVN: $Id$
 * @link
 * @since      File available since Release 1.0.0
 */

namespace HTML;

class Dom
{

    /**
     * @var USE_CACHE
     */
    const USE_CACHE = true;

    /**
     * @param array $defaults
     */
    public static $defaults = ['element' => 'div', 'attributes' => [], 'content' => ''];

    /**
     * @var ELEMENT
     */
    const ELEMENT = '<{{ELEMENT}} {{ATTRIBUTES}}>{{CONTENT}}</{{ELEMENT}}>';

    public function __construct()
    {

    }

    /**
     * array(
     * element=>'div',
     * attributes=>array(),
     * content=>array
     * array(
     * element=>'div',
     * attributes=>array(),
     * content=>array(
     * array(
     * element=>'div',
     * attributes=>array(),
     * content=>array()
     * ),
     * array(
     * element=>'div',
     * attributes=>array(),
     * content=>array()
     * )
     * )
     * ),
     * array(
     * element=>'div',
     * attributes=>array(),
     * content=>array()
     * )
     * )
     * ....
     * )
     * )
     */

    /**
     * @param array $elements
     * @param string $content
     * @return $content
     */
    public static function generator(array $elements, $content = '')
    {
        if (isset($elements['content']) && is_string($elements['content'])) {
            $elements = [$elements];
        }
        if (count($elements) > 0) {
            foreach ($elements as $element) {
                //c($element);
                if (isset($element['content']) && is_array($element['content']) && count($element['content']) > 0) {
                    $element['content'] = self::generator($element['content'], $content);
                }
                if (is_array($element)) {
                    $content .= self::core($element);
                }
            }
        }
        return $content;
    }

    /**
     * @param array $element
     * @access private
     */
    private static function core(array $element)
    {
        if (isset($element['attributes']) && is_array($element['attributes'])) {
            if (count($element['attributes']) > 0) {
                $element['attributes'] = self::makeAttr($element);
            } else {
                $element['attributes'] = '';
            }
        } else {
            $element['attributes'] = '';
        }
        return self::element($element);
    }

    /**
     * @param array $data
     * @access private
     */
    private static function element(array $data)
    {
        //c($data);
        $data = set_defaults($data, self::$defaults);
        //c($data);
        return Template::embed($data, self::ELEMENT, [
            'use' => 'd'
        ]);
    }

    /**
     * @param array $elements
     * @param array $conf
     * @param string $element_type
     * @return array $output
     */
    public static function elementGenerator(array $elements, array $conf, $element_type = 'content')
    {
        if (count($elements) > 0) {
            $conf = set_defaults($conf, self::$defaults);
            $output = [];
            foreach ($elements as $element) {
                $conf[$element_type] = $element;
                $output[] = $conf;
            }
            return $output;
        }
    }

    /**
     * @param array $attr_defaults
     */
    public static $attr_defaults = ['prefix' => '', 'cache' => true];

    public static function base64_decode($value)
    {
        return json_decode(base64_decode(str_replace('__BASE64', '', str_replace(['XYZYXY', 'CXCXCXCXCX'], ['=', '+'], $value)), true), true);
    }

    public static function base64($value)
    {
        $value = str_replace(['=', '+'], ['XYZYXY', 'CXCXCXCXCX'], base64_encode(json_encode($value)) . '__BASE64');
        return $value;
    }

    /**
     * @param array $config :
     * array(
     * 'prefix'=>'',
     * attributes=>array(
     * 'name'=>'',
     * ),
     * 'groups'=>array(
     * 'prefix'=>'',
     * 'attributes'=>array(
     * 'name'=>'',
     * 'id'=>''
     * )
     * )
     *
     */

    /**
     * @param array $conf
     */
    public static function makeAttr(array $conf)
    {
        if (isset($conf['fields'])) {
            $conf['attributes'] = $conf['fields'];
            unset($conf['fields']);
        }
        $conf = set_defaults($conf, self::$attr_defaults);
        $has_field = (isset($conf['groups']['attributes']) && is_array($conf['groups']['attributes'])) || (isset($conf['attributes']) && is_array($conf['attributes']));
        if ($has_field) {
            $is_cache = self::USE_CACHE && $conf['cache'];
            if ($is_cache) {

                $cache_key = __FUNCTION__ . md5(serialize(func_get_args()));
                if ($result = \Cache::get($cache_key)) {
                    return $result;
                }
            }
            $cikti = "";
            if (isset($conf['attributes'])) {
                $islem = '=';
                $operator_nick = ' ';
                foreach ($conf['attributes'] as $key => $value) {
                    if (is_array($value)) {
                        $value = self::base64($value);
                    }
                    $cikti .= $conf['prefix'] . $key . $islem . '"' . $value . '"' . $operator_nick;
                }
            }
            if (isset($conf['groups']) && is_array($conf['groups'])) {
                // Mirası tekrar atıyoruz
                if (!isset($conf['groups']['cache']) && isset($conf['cache'])) {
                    $conf['groups']['cache'] = $conf['cache'];
                }
                if (!isset($conf['groups']['prefix']) && isset($conf['prefix'])) {
                    $conf['groups']['prefix'] = $conf['prefix'];
                }
                $cikti .= self::{__FUNCTION__}($conf['groups']);
            }

            if ($is_cache) {
                \Cache::put($cache_key, $cikti);
            }

            return $cikti;
        }

        return '';
    }

    public function __destruct()
    {

    }

}
