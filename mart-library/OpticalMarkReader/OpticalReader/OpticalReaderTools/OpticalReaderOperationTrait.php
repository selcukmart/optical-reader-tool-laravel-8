<?php
/**
 * @author selcukmart
 * 23.11.2021
 * 11:21
 */

namespace OpticalMarkReader\OpticalReader\OpticalReaderTools;

use OpticalMarkReader\OpticalReader\OpticalReaderManager;

trait OpticalReaderOperationTrait
{
    private
        $opticalReaderManager;

    public function __construct(OpticalReaderManager $opticalReaderManager)
    {
        $this->opticalReaderManager = $opticalReaderManager;
    }

    public static function instance(OpticalReaderManager $opticalReaderManager)
    {
        return new self($opticalReaderManager);
    }

    private function getCompanyID()
    {
        return $this->opticalReaderManager->getData()['company_id'];
    }

    public function __destruct()
    {

    }
}
