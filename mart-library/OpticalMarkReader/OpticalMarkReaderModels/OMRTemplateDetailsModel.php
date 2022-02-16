<?php
/**
 * @author selcukmart
 * 21.11.2021
 * 17:53
 */

namespace OpticalMarkReader\OpticalMarkReaderModels;

use DB\DbModelTrait;

class OMRTemplateDetailsModel
{
    use DbModelTrait;

    const TABLE = 'omr_template_details';
    const TABLE_AS = 'otd';
}
