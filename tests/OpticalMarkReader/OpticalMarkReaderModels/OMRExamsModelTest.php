<?php
/**
 * @author selcukmart
 * 20.12.2021
 * 10:47
 */

namespace Tests\OpticalMarkReader\OpticalMarkReaderModels;

use OpticalMarkReader\OpticalMarkReaderModels\OMRExamsModel;
use PHPUnit\Framework\TestCase;

class OMRExamsModelTest extends TestCase
{
    public function testTable()
    {
        $this->assertEquals('omr_exams', OMRExamsModel::table());
    }
}
