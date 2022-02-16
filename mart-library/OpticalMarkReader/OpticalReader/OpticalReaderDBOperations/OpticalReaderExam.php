<?php
/**
 * @author selcukmart
 * 23.11.2021
 * 11:47
 */

namespace OpticalMarkReader\OpticalReader\OpticalReaderDBOperations;

use DB;
use OpticalMarkReader\OpticalMarkReaderModels\OMRExamsModel;
use OpticalMarkReader\OpticalReader\OpticalReaderTools\OpticalReaderOperationTrait;

class OpticalReaderExam
{
    use OpticalReaderOperationTrait;


    public function getVariables()
    {
        $exam_id = $this->opticalReaderManager->getData()['exam_id'];
        $table = OMRExamsModel::table();
        $arr = [
            'id' => $exam_id,
            'company_id' => $this->getCompanyID()
        ];
        $control = (array)DB::table($table)->where($arr)->limit(1)->first();
        $this->opticalReaderManager->setExamId($control['id']);
        $this->opticalReaderManager->add2Data('template_id', $control['template_id']);
    }

}
