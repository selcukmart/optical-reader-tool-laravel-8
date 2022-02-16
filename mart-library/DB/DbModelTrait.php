<?php
/**
 * @author selcukmart
 * 23.09.2021
 * 13:18
 */

namespace DB;


trait DbModelTrait
{
    /**
     * Tabloda belirtilen ön ek sabit tanımlımı
     */
    public static function table()
    {
        return self::TABLE;
    }
}
