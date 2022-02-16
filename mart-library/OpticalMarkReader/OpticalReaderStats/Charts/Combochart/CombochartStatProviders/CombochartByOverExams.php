<?php
/**
 * @author selcukmart
 * 4.12.2021
 * 15:51
 */

namespace OpticalMarkReader\OpticalReaderStats\Charts\Combochart\CombochartStatProviders;

use OpticalMarkReader\OpticalMarkReaderModels\OMRExamsModel;
use OpticalMarkReader\OpticalMarkReaderModels\OMRExamStatsModel;
use OpticalMarkReader\OpticalMarkReaderModels\OMRTemplateDetailsModel;
use OpticalMarkReader\OpticalMarkReaderModels\OMRTemplatesModel;
use OpticalMarkReader\OpticalMarkReaderModels\OMRUserExamStatsModel;
use OpticalMarkReader\OpticalMarkReaderModels\OMRUserExamTemplateDetailStatsModel;
use OpticalMarkReader\OpticalReaderStats\Charts\Combochart\OpticalReaderUserExamStatsCombochart;


class CombochartByOverExams
{
    private
        $user_id,
        $chart_data,
        $charts_first_data = [],
        $exams = [],
        $by,
        $template_data,
        $OpticalReaderUserExamStatsCombochart;

    public function __construct(OpticalReaderUserExamStatsCombochart $OpticalReaderUserExamStatsCombochart)
    {
        $this->OpticalReaderUserExamStatsCombochart = $OpticalReaderUserExamStatsCombochart;
        $this->user_id = $this->OpticalReaderUserExamStatsCombochart->getUserId();
        $this->template_data = Db::verisi($this->OpticalReaderUserExamStatsCombochart->getExamData()['template_id'], OMRTemplatesModel::table());
        $this->template_datails = Db::fetch_all_by_arr([
            'type' => 'question',
            'template_id' => $this->template_data['id']
        ], OMRTemplateDetailsModel::table());

        $this->vtable = OMRUserExamTemplateDetailStatsModel::table();
        $this->vtable_as = OMRUserExamTemplateDetailStatsModel::TABLE_AS;
    }

    public function getData($by)
    {
        $this->by = $by;
        $this->getLastExams();

        $this->chart_data = [$this->getChartFirstData()];
        $a = 0;
        foreach ($this->exams as $exam) {
            $a++;
            $this->chart_data[$a][0] = $exam['name'];
            $this->chart_data[$a][1] = $exam[$by];
            $this->chart_data[$a][2] = $this->getExamStat($exam['exam_id']);
        }
        return $this->chart_data;
    }

    private function getExamStat($exam_id)
    {
        $field = 'average_'.$this->by;
        if(isset($this->exam_stats[$exam_id][$field])){
            return $this->exam_stats[$exam_id][$field];
        }
        $this->exam_stats[$exam_id] = Db::fetchOneDataByArray([
            'exam_id' => $exam_id
        ], OMRExamStatsModel::table());
        return $this->exam_stats[$exam_id][$field];
    }

    private function getChartFirstData()
    {
        if (!empty($this->charts_first_data)) {
            return $this->charts_first_data;
        }
        $this->charts_first_data[0] = 'Sınav';
        $this->charts_first_data[] = $this->by;
        $this->charts_first_data[] = 'Ortalama';
        return $this->charts_first_data;
    }

    private function getLastExams()
    {
        if (!empty($this->exams)) {
            return $this->exams;
        }
        $vtable = OMRUserExamStatsModel::table();
        $vtable_as = OMRUserExamStatsModel::TABLE_AS;

        $vtable2 = OMRExamsModel::table();
        $vtable2_as = OMRExamsModel::TABLE_AS;

        $vtable4 = OMRTemplatesModel::table();
        $vtable4_as = OMRTemplatesModel::TABLE_AS;

        $sql = "SELECT
            $vtable_as.exam_id,
            $vtable2_as.name,
            $vtable_as.score,
            $vtable_as.net
        FROM $vtable $vtable_as
            INNER JOIN $vtable2 $vtable2_as ON $vtable2_as.id=$vtable_as.exam_id
            INNER JOIN $vtable4 $vtable4_as ON $vtable4_as.id=$vtable2_as.template_id
        WHERE
            $vtable_as.user_id='" . $this->user_id . "'
            AND $vtable4_as.type='" . $this->template_data['type'] . "'
        ORDER BY $vtable_as.id DESC LIMIT 3";
        $this->exams = Db::fetch_all($sql);
    }

    public function __destruct()
    {

    }
}
