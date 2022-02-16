<?php
/**
 * @author selcukmart
 * 19.12.2021
 * 17:26
 */

function _e($msgid, $msgctxt = null, $args = null)
{
    if (empty($msgid)) {
        return '';
    }
    echo ___($msgid, $msgctxt, $args);
}

function ___($msgid, $args = null)
{
    return $msgid;
//    if (is_null($msgid)) {
//        $msgid = 'Başlıksız';
//    }
//
//    $msgctxt = null;
//    return __($msgid, $msgctxt, $args);
}



function mb_ucfirst($string, $encoding = 'UTF-8')
{
    $strlen = mb_strlen($string, $encoding);
    $firstChar = mb_substr($string, 0, 1, $encoding);
    $then = mb_substr($string, 1, $strlen - 1, $encoding);
    return mb_strtoupper($firstChar, $encoding) . $then;
}

function _sizeof($data)
{
    if (is_countable($data)) {
        return count($data);
    }
}

function defaults(array $conf, $defaults)
{
    return set_defaults($conf, $defaults);
}

function set_defaults(array $conf, $defaults)
{
    foreach ($defaults as $key => $value) {
        $is_array = is_array($value) && count($value) > 0;
        if ($is_array) {
            if (!isset($conf[$key])) {
                $conf[$key] = [];
            }
            $conf[$key] = set_defaults($conf[$key], $value);
        } elseif (!isset($conf[$key])) {
            $conf[$key] = $value;
        }
    }
    $conf['__is_def'] = true;
    return $conf;
}

function c($v, $return = false)
{
    if ($return) {
        $output = '<pre>';
    } else {
        echo '<pre>';
    }
    if (is_array($v) || is_object($v)) {
        if ($return) {
            $output .= print_r($v, true);
        } else {
            print_r($v, false);
        }
    } else {
        if ($return) {
            $output .= $v;
        } else {
            if (is_bool($v)) {
                var_dump($v);
            } else {
                echo $v;
            }
        }
    }
    if ($return) {
        $output .= '</pre>';
        return $output;
    }

    echo '</pre>';
}
