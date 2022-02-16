<?php
/**
 * @author selcukmart
 * 21.11.2021
 * 17:54
 */

namespace OpticalMarkReader\OpticalMarkReaderModels;

use DB\DbModelTrait;

class OMRUserExamStatsModel
{
    use DbModelTrait;

    const TABLE = 'omr_user_exam_stats';
    const TABLE_AS = 'oues';

}
