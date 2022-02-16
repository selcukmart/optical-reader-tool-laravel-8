<?php
/**
 * @author selcukmart
 * 4.12.2021
 * 17:23
 */

namespace OpticalMarkReader\OpticalReaderStats;

use OpticalMarkReader\OpticalMarkReaderModels\OMRExamsModel;
use OpticalMarkReader\OpticalMarkReaderModels\OMRTemplateDetailsModel;
use OpticalMarkReader\OpticalMarkReaderModels\OMRUserExamStatsModel;
use OpticalMarkReader\OpticalMarkReaderModels\OMRUsersModel;


class OpticalReaderFullListRankedWithScore
{
    private
        $tpl = 'optical-reader-user-exam-stats/full-list-ranked-with-score.tpl',
        $exam_data,
        $template_details,
        $user_exam_stats,
        $exam_id;

    public function __construct($exam_id)
    {
        $this->exam_id = $exam_id;
        $this->exam_data = Db::verisi($this->exam_id, OMRExamsModel::table());
        $this->template_details = Db::fetch_all_by_arr([
            'type' => 'question',
            'template_id' => $this->exam_data['template_id']
        ], OMRTemplateDetailsModel::table());

        $this->getStudentsLists();
    }

    private function getStudentsLists()
    {
        $vtable = OMRUserExamStatsModel::table();
        $vtable_as = OMRUserExamStatsModel::TABLE_AS;

        $vtable2 = OMRUsersModel::table();
        $vtable2_as = OMRUsersModel::TABLE_AS;

        $sql = "SELECT
         $vtable_as.*,
        $vtable2_as.name_surname
         FROM $vtable $vtable_as
            INNER JOIN $vtable2 $vtable2_as ON $vtable2_as.id=$vtable_as.user_id
        WHERE
            $vtable_as.exam_id='" . $this->exam_id . "'
        ORDER BY $vtable_as.score DESC
         ";
        $this->user_exam_stats = Db::fetch_all($sql);
    }

    public function output()
    {
        global $admin_smarty;
        $admin_smarty->assign('exam', $this->exam_data);
        $admin_smarty->assign('row_table', $this->exam_data);
        $admin_smarty->assign('exam_parts', $this->template_details);
        $admin_smarty->assign('user_exam_stats', $this->user_exam_stats);
        $export_type = 'table';
        if(isset($_GET['export_type'])){
            $export_type = $_GET['export_type'];
        }
        $admin_smarty->assign('export_type', $export_type);
        return $admin_smarty->fetch($this->tpl);
    }

    public static function userExamTemplateDetailStats($exam_id, $user_id)
    {
        $OpticalReaderUserExamStatsWithTemplateDetails = new OpticalReaderUserExamStatsWithTemplateDetails($exam_id, $user_id);
        return $OpticalReaderUserExamStatsWithTemplateDetails->getArray();
    }

    public function __destruct()
    {

    }
}
