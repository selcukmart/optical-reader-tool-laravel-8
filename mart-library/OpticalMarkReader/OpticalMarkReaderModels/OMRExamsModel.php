<?php
/**
 * @author selcukmart
 * 21.11.2021
 * 17:53
 */

namespace OpticalMarkReader\OpticalMarkReaderModels;

use DB\DbModelTrait;

class OMRExamsModel
{
    use DbModelTrait;

    const TABLE = 'omr_exams';
    const TABLE_AS = 'oe';
}
