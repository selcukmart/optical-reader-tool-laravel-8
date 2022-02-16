<?php
/**
 * @author selcukmart
 * 1.12.2021
 * 11:59
 */

namespace OpticalMarkReader\OpticalReaderStats;

use OpticalMarkReader\OpticalMarkReaderModels\OMRExamsModel;
use OpticalMarkReader\OpticalMarkReaderModels\OMRExamStatsModel;
use OpticalMarkReader\OpticalMarkReaderModels\OMRTemplateDetailsModel;
use OpticalMarkReader\OpticalMarkReaderModels\OMRTemplatesModel;
use OpticalMarkReader\OpticalMarkReaderModels\OMRUserExamStatsModel;
use OpticalMarkReader\OpticalMarkReaderModels\OMRUserExamTemplateDetailStatsModel;
use OpticalMarkReader\OpticalMarkReaderModels\OMRUsersModel;
use GlobalTraits\ErrorMessagesWithResultTrait;


class OpticalReaderUserExamReport
{
    use ErrorMessagesWithResultTrait;

    private
        $tpl = 'optical-reader-user-exam-stats/user-exam-report.tpl',
        $user_id,
        $user_data,
        $exam_id,
        $exam_data,
        $output,
        $template_detail_stats = [];

    public function __construct(int $user_id, int $exam_id)
    {
        $this->exam_id = $exam_id;
        $this->user_id = $user_id;
        $this->exam_data = Db::verisi($this->exam_id, OMRExamsModel::table());
        $this->user_data = Db::verisi($this->user_id, OMRUsersModel::table());
        $this->user_exam_stats_data = Db::fetchOneDataByArray([
            'user_id' => $this->user_id,
            'exam_id' => $exam_id
        ], OMRUserExamStatsModel::table());
        $this->exam_stats_data = Db::fetchOneDataByArray([
            'exam_id' => $exam_id
        ], OMRExamStatsModel::table());
        $this->template_data = Db::verisi($this->exam_data['template_id'], OMRTemplatesModel::table());
    }

    public function print()
    {
        if (empty($this->exam_data)) {
            $this->setErrorMessage('Böyle bir sınav bulunamadı');
            return $this->isResult();
        }

        if (empty($this->user_data)) {
            $this->setErrorMessage('Böyle bir kullanıcı bulunamadı');
            return $this->isResult();
        }
        if (empty($this->user_exam_stats_data)) {
            $this->setErrorMessage('Böyle bir kullanıcı sınavı bulunamadı');
            return $this->isResult();
        }
        if (!is_null($this->output)) {
            return $this->output;
        }
        global $admin_smarty;
        $arr = array_merge($this->user_data, $this->exam_data, $this->user_exam_stats_data, $this->exam_stats_data);
        $arr['exam_name'] = $this->exam_data['name'];
        $arr['exam_id'] = $this->exam_data['id'];
        $arr['exam_date'] = \Zaman::set_gun_saat($this->exam_data['date']);
        $admin_smarty->assign('row', $arr);
        $this->getTemplateDetailStats();
        $admin_smarty->assign('all_lessons', $this->template_detail_stats);
        $admin_smarty->assign('OpticalReaderUserExamReport', $this);
        $this->output = $admin_smarty->fetch($this->tpl);
        $this->setResult(true);

        return $this->output;
    }

    private function getTemplateDetailStats()
    {
        $vtable = OMRUserExamTemplateDetailStatsModel::table();
        $vtable_as = OMRUserExamTemplateDetailStatsModel::TABLE_AS;

        $vtable2 = OMRTemplateDetailsModel::table();
        $vtable2_as = OMRTemplateDetailsModel::TABLE_AS;
        $sql = "SELECT
            $vtable2_as.name,
            ($vtable2_as.finish_line - $vtable2_as.start_line) + 1 AS question_count,
            $vtable_as.*
        FROM $vtable $vtable_as
            INNER JOIN $vtable2 $vtable2_as ON $vtable2_as.id=$vtable_as.template_detail_id
        WHERE
            $vtable_as.exam_id='" . $this->exam_id . "'
            AND $vtable_as.user_id='" . $this->user_id . "'
            ";
        $query = \DB::statement($sql);
        $path = __NAMESPACE__ . '\UserExamReportTemplateDetailStatsArray\\';
        $classname = $path . strtoupper($this->template_data['type']);
        if (!class_exists($classname)) {
            $classname = $path . 'Generic';
        }
        $class = new $classname($query);
        $this->template_detail_stats = $class->getTemplateDetailStats();
        return $this->template_detail_stats;
    }

    public static function getPrint(int $user_id, int $exam_id)
    {
        if (!($user_id && $exam_id)) {
            return;
        }
        $name = 'OpticalReaderUserExamReport' . $user_id . '_' . $exam_id;
        global $$name;
        if (!is_null($$name)) {
            return $$name->getOutput();
        }
        $$name = new self($user_id, $exam_id);
        return $$name->print();

    }

    /**
     * @return mixed
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @return mixed
     */
    public function getTemplateData()
    {
        return $this->template_data;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * @return int
     */
    public function getExamId(): int
    {
        return $this->exam_id;
    }

    public function __destruct()
    {

    }
}
