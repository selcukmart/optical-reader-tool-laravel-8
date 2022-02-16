<?php

class Classes
{
    public static
        $exceptionals = [
        'cc' => 'CC'
    ];

    public function __construct()
    {
    }

    public static function format($str)
    {
        $str = (string)$str;
        if (!is_numeric($str)) {
            $x = explode('_', strtolower($str));
            $xstr = '';
            foreach ($x as $value) {
                $xstr .= ucfirst($value);
            }
            $kxstr = strtolower($xstr);
            if (array_key_exists($kxstr, self::$exceptionals)) {
                $xstr = self::$exceptionals[$kxstr];
            }
            if (class_exists($xstr)) {
                return $xstr;
            }
        }

        return false;
    }

    public static function name($str)
    {
        $str = (defined('TABLE_PREFIX')) ? preg_replace('/^' . TABLE_PREFIX . '/', '', $str) : $str;
        $str = (string)$str;

        if (!is_numeric($str)) {
            $x = explode('_', mb_strtolower($str));
            $xstr = '';
            foreach ($x as $value) {
                $xstr .= mb_ucfirst($value);
            }

            return $xstr;
        }

        return false;
    }

    public static function prepareFromString($str)
    {
        $str = str_replace('/', '_', $str);
        $x = explode('_', mb_strtolower($str));
        $xstr = '';
        foreach ($x as $value) {
            $xstr .= mb_ucfirst($value);
        }
        return $xstr;
    }

    public static function prepareFromString4Controller($str): string
    {
        $explode = explode('/', $str);
        $class = $explode[count($explode) - 1];
        unset($explode[count($explode) - 1]);
        $namespace = '';
        foreach ($explode as $item) {
            $namespace .= mb_ucfirst($item) . '\\';
        }
        $namespace = ltrim($namespace, '\\');
        $class = str_replace('-', '_', $class);
        $x = explode('_', mb_strtolower($class));
        $xstr = '';
        foreach ($x as $key => $value) {
            $xstr .= mb_ucfirst($value);
        }
        return $namespace . $xstr;
    }

    public static function prepareMethodNameFromString($str)
    {
        $str = str_replace('-', '_', $str);
        $x = explode('_', mb_strtolower($str));
        $xstr = mb_strtolower($x[0]);
        unset($x[0]);
        foreach ($x as $key => $value) {
            $xstr .= mb_ucfirst($value);
        }
        return $xstr;
    }

    public static function prepareClassName($str)
    {
        $str = str_replace('-', '_', $str);
        $x = explode('_', mb_strtolower($str));
        $xstr = '';
        foreach ($x as $value) {
            $xstr .= mb_ucfirst($value);
        }
        return $xstr;
    }

    public function __destruct()
    {
    }
}
