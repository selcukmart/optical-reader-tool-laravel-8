<?php
/**
 * @author selcukmart
 * 3.12.2021
 * 14:39
 */

namespace OpticalMarkReader\OpticalReaderStats;

use OpticalMarkReader\OpticalMarkReaderModels\OMRTemplateDetailsModel;
use OpticalMarkReader\OpticalMarkReaderModels\OMRUserAnswersModel;


class OpticalReaderComparisonExamStudentAnswers
{
    private
        $output,
        $admin_smarty,
        $template_datails,
        $opticalReaderUserExamReport;

    public function __construct(OpticalReaderUserExamReport $opticalReaderUserExamReport)
    {
        global $admin_smarty;
        $this->admin_smarty = $admin_smarty;
        $this->opticalReaderUserExamReport = $opticalReaderUserExamReport;

        $this->template_datails = Db::fetch_all_by_arr([
            'type' => 'question',
            'template_id' => $this->opticalReaderUserExamReport->getTemplateData()['id']
        ], OMRTemplateDetailsModel::table());
    }

    public function getOutput()
    {
        if (!is_null($this->output)) {
            return $this->output;
        }

        $this->admin_smarty->assign('OpticalReaderComparisonExamStudentAnswers', $this);
        $this->admin_smarty->assign('exam_parts', $this->template_datails);
        $this->output = $this->admin_smarty->fetch('optical-reader-user-exam-stats/answers-diffs/template-detail.tpl');
        return $this->output;
    }

    public static function print(OpticalReaderUserExamReport $opticalReaderUserExamReport)
    {
        $OpticalReaderComparisonExamStudentAnswers = new self($opticalReaderUserExamReport);
        return $OpticalReaderComparisonExamStudentAnswers->getOutput();
    }

    public function getList($exam_part)
    {
        $arr = [
            'user_id' => $this->opticalReaderUserExamReport->getUserId(),
            'exam_id' => $this->opticalReaderUserExamReport->getExamId(),
            'template_detail_id' => $exam_part['id']
        ];
        $this->admin_smarty->assign('query', Db::select($arr, OMRUserAnswersModel::table(), $limit = null, $debug = false, $log = false, $order_by = " id DESC"));
        return $this->admin_smarty->fetch('optical-reader-user-exam-stats/answers-diffs/lists-group.tpl');
    }

    public function __destruct()
    {

    }
}
