<?php
/**
 * @author selcukmart
 * 21.11.2021
 * 17:53
 */

namespace OpticalMarkReader\OpticalMarkReaderModels;

use DB\DbModelTrait;

class OMRExamStatsModel
{
    use DbModelTrait;

    const TABLE = 'omr_exam_stats';
    const TABLE_AS = 'oes';
}
