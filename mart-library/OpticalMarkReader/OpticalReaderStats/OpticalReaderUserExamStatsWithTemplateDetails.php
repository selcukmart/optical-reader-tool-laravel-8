<?php
/**
 * @author selcukmart
 * 28.11.2021
 * 16:43
 */

namespace OpticalMarkReader\OpticalReaderStats;

use OpticalMarkReader\OpticalMarkReaderModels\OMRTemplateDetailsModel;
use OpticalMarkReader\OpticalMarkReaderModels\OMRUserExamTemplateDetailStatsModel;


class OpticalReaderUserExamStatsWithTemplateDetails
{
    private
        $user_id,
        $exam_id;

    public function __construct(int $exam_id, int $user_id)
    {
        $this->exam_id = $exam_id;
        $this->user_id = $user_id;
    }

    public static function html(int $exam_id, int $user_id)
    {
        $self = new self($exam_id,$user_id);
        return $self->getAsHTML();
    }

    public function getAsHTML()
    {
        global $admin_smarty;
        $lists = $this->getArray();
        $tpl = 'optical-reader-user-exam-stats/list-group.ul.tpl';
        $admin_smarty->assign('lists', $lists);
        return $admin_smarty->fetch($tpl);
    }

    public function getArray()
    {
        $vtable = OMRUserExamTemplateDetailStatsModel::table();
        $vtable_as = OMRUserExamTemplateDetailStatsModel::TABLE_AS;

        $vtable2 = OMRTemplateDetailsModel::table();
        $vtable2_as = OMRTemplateDetailsModel::TABLE_AS;

        $sql = "SELECT
                        *,
                        $vtable2_as.name
                    FROM $vtable   $vtable_as
                        INNER JOIN $vtable2 $vtable2_as ON $vtable2_as.id=$vtable_as.template_detail_id
                    WHERE
                        $vtable_as.exam_id='" . $this->exam_id . "'
                        AND $vtable_as.user_id='" . $this->user_id . "'
                    ";

        return Db::fetch_all($sql);
    }

    public function __destruct()
    {

    }
}
