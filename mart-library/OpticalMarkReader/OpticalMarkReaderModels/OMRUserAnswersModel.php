<?php
/**
 * @author selcukmart
 * 21.11.2021
 * 17:54
 */

namespace OpticalMarkReader\OpticalMarkReaderModels;

use DB\DbModelTrait;

class OMRUserAnswersModel
{
    use DbModelTrait;

    const TABLE = 'omr_user_answers';
    const TABLE_AS = 'oua';

    public static $answer_label = [
        'right' => '<span class="label label-success">Doğru Bildi</span>',
        'wrong' => '<span class="label label-danger">Yanlış Bildi</span>',
        'null' => '<span class="label label-warning">Boş Bıraktı</span>',
        '' => '<span class="">Belirlenemedi</span>',
    ];

    /**
     * @return string[]
     */
    public static function getAnswerLabel($label): string
    {
        return isset(self::$answer_label[$label]) ? self::$answer_label[$label] : '';
    }
}
