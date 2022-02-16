<?php
/**
 * @author selcukmart
 * 21.11.2021
 * 17:54
 */

namespace OpticalMarkReader\OpticalMarkReaderModels;

use DB\DbModelTrait;

class OMRQuestionsModel
{
    use DbModelTrait;

    const TABLE = 'omr_questions';
    const TABLE_AS = 'oq';
}
