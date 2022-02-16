<?php
/**
 * @author selcukmart
 * 4.12.2021
 * 15:51
 */

namespace OpticalMarkReader\OpticalReaderStats\Charts\Combochart\CombochartStatProviders;

use OpticalMarkReader\OpticalMarkReaderModels\OMRExamsModel;
use OpticalMarkReader\OpticalMarkReaderModels\OMRTemplateDetailsModel;
use OpticalMarkReader\OpticalMarkReaderModels\OMRTemplatesModel;
use OpticalMarkReader\OpticalMarkReaderModels\OMRUserExamStatsModel;
use OpticalMarkReader\OpticalMarkReaderModels\OMRUserExamTemplateDetailStatsModel;
use OpticalMarkReader\OpticalReaderStats\Charts\Combochart\OpticalReaderUserExamStatsCombochart;


class CombochartByOverTemplateDetails
{
    private
        $user_id,
        $chart_data,
        $charts_first_data = [],
        $exams = [],
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
        $this->getLastExams();
        $vtable = $this->vtable;
        $vtable_as = $this->vtable_as;

        $this->chart_data = [$this->getChartFirstData()];
        $a = 0;
        $continue = false;
        foreach ($this->exams as $exam) {
            $a++;
            if ($continue) {
                $a--;
                $continue = false;
            }

            foreach ($this->template_datails as $template_datail) {
                $sql = "SELECT
                $vtable_as.$by
            FROM $vtable $vtable_as
            WHERE
               $vtable_as.exam_id='" . $exam['exam_id'] . "'
               AND $vtable_as.user_id ='" . $this->user_id . "'
               AND $vtable_as.template_detail_id ='" . $template_datail['id'] . "'
            ";
                $query = \DB::statement($sql);
                $total = Db::row_count($query);
                if (!$total) {
                    $continue = true;
                    continue;
                }

                if (!isset($this->chart_data[$a][0])) {
                    $this->chart_data[$a][0] = $exam['name'];
                    $b = 0;
                }

                foreach ($query as $item) {
                    $b++;
                    $this->chart_data[$a][$b] = $item[$by];
                }
            }

        }

        return $this->chart_data;
    }

    private function getChartFirstData()
    {
        if (!empty($this->charts_first_data)) {
            return $this->charts_first_data;
        }
        $this->charts_first_data[0] = 'Sınav';
        foreach ($this->template_datails as $template_datail) {

            $this->charts_first_data[] = $template_datail['name'];
        }
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
            $vtable2_as.name
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
