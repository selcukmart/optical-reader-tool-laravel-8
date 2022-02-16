<?php
/**
 * @author selcukmart
 * 21.11.2021
 * 17:52
 */

namespace OpticalMarkReader\OpticalMarkReaderModels;

use DB\DbModelTrait;

class OMRTemplatesModel
{
    use DbModelTrait;

    const TABLE = 'omr_templates';
    const TABLE_AS = 'ot';
}
