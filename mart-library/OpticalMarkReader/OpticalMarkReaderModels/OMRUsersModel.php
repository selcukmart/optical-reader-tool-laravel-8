<?php
/**
 * @author selcukmart
 * 21.11.2021
 * 17:54
 */

namespace OpticalMarkReader\OpticalMarkReaderModels;

use DB\DbModelTrait;

class OMRUsersModel
{
    use DbModelTrait;

    const TABLE = 'omr_users';
    const TABLE_AS = 'ou';
}
