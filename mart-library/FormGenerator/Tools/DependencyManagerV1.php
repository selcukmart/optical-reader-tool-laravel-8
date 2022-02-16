<?php
/**
 * @author selcukmart
 * 29.01.2021
 * 00:09
 */

namespace FormGenerator\Tools;


class DependencyManagerV1
{
    public function __construct()
    {
    }

    public static function dependend($item)
    {
        $dependent_attributes = '';
        if (isset($item['dependend']['group'], $item['dependend']['dependend'])) {
            $dependent_attributes .= ' data-dependends="true" data-dependend-group="' . $item['dependend']['group'] . '"
                                      data-dependend="' . $item['dependend']['dependend'] . '" ';
        }

//        if (isset($item['dependency']) && $item['dependency']) {
//            $dependent_attributes .= ' data-dependency="true" data-dependency-group="' . $item['attributes']['name'] . '"';
//        }
        return $dependent_attributes;
    }

    public function __destruct()
    {

    }
}
