<?php
/**
 * @author selcukmart
 * 28.11.2021
 * 16:43
 */

namespace OpticalMarkReader\OpticalReaderStats;

use Exam\NetCalculate;
use OpticalMarkReader\OpticalMarkReaderModels\OMRExamsModel;
use OpticalMarkReader\OpticalMarkReaderModels\OMRExamStatsModel;
use OpticalMarkReader\OpticalMarkReaderModels\OMRTemplateDetailsModel;
use OpticalMarkReader\OpticalMarkReaderModels\OMRTemplatesModel;
use OpticalMarkReader\OpticalMarkReaderModels\OMRUserAnswersModel;
use OpticalMarkReader\OpticalMarkReaderModels\OMRUserExamStatsModel;
use OpticalMarkReader\OpticalMarkReaderModels\OMRUserExamTemplateDetailStatsModel;


class OpticalReaderUserExamStats
{
    private
        $force = false,
        $generated,
        $booklets = [],
        $total_right_answers = 0,
        $total_wrong_answers = 0,
        $total_null_answers = 0,
        $total_score = 0,
        $total_net = 0,
        $total_all_participant = 0,
        $total_course_participant = 0,
        $template_data,
        $template_details_data,
        $exam_data,
        $exam_option_count,
        $exam_type_class,
        $exam_id;

    public function __construct(int $exam_id)
    {
        $this->exam_id = $exam_id;
        $this->exam_data = Db::verisi($this->exam_id, OMRExamsModel::table());
    }

    public function generate()
    {
        if (!$this->isGenerated() || $this->isForce()) {
            $query = $this->getQuery();
            $stats_arr = $this->getStatsArr($query);
            $this->insert2DB($stats_arr);
        }
    }

    private function getTemplateData()
    {
        $this->template_data = Db::verisi($this->exam_data['template_id'], OMRTemplatesModel::table());
    }

    private function getTemplateDetailData()
    {
        $sql = "SELECT
            id,
            factor
        FROM " . OMRTemplateDetailsModel::table() . "
        WHERE
        template_id='" . $this->exam_data['template_id'] . "'
        AND type='question'";
        $query = \DB::statement($sql);
        foreach ($query as $item) {
            $this->template_details_data[$item['id']] = $item['factor'];
        }

    }

    /**
     * @return mixed
     */
    private function getExamOptionCount()
    {
        if (!is_null($this->exam_option_count)) {
            return $this->exam_option_count;
        }
        $this->getTemplateData();
        $type = strtoupper($this->template_data['type']);
        $this->exam_type_class = '\Exam\ExamTypes\\' . $type;
        $this->exam_option_count = constant($this->exam_type_class . '::OPTION_COUNT');
        return $this->exam_option_count;
    }

    private function isGenerated()
    {
        if ($this->generated) {
            return $this->generated;
        }
        $arr = [
            'exam_id' => $this->exam_id
        ];
        $this->generated = Db::varlik_kontrolu($arr, OMRUserExamStatsModel::table()) ? true : false;
        return $this->generated;
    }

    /**
     * @param bool $force
     */
    public function setForce(bool $force): void
    {
        $this->force = $force;
    }

    /**
     * @return int
     */
    public function getExamId(): int
    {
        return $this->exam_id;
    }

    /**
     * @return bool
     */
    public function isForce(): bool
    {
        return $this->force;
    }


    private function getQuery()
    {
        $vtable = OMRUserAnswersModel::table();
        $sql = "SELECT
                        COUNT(*) AS TOPLAM_VERI,
                        user_id,
                        booklet,
                        template_detail_id,
                        answer
                    FROM $vtable
                    WHERE
                        exam_id='" . $this->exam_id . "'
                    GROUP BY
                        user_id,
                        template_detail_id,
                        answer
                     ORDER BY
                     user_id,
                        template_detail_id,
                        answer";

        $query = \DB::statement($sql);
        return $query;
    }

    /**
     * @param $query
     * @return array
     * @author selcukmart
     * 28.11.2021
     * 17:49
     */
    private function getStatsArr($query): array
    {
        $stats_arr = [];
        foreach ($query as $item) {
            if (!isset($stats_arr[$item['user_id']][$item['template_detail_id']])) {
                $stats_arr[$item['user_id']][$item['template_detail_id']] = [];
            }
            if ($item['answer'] === 'right') {
                $stats_arr[$item['user_id']][$item['template_detail_id']]['rights_count'] = $item['TOPLAM_VERI'];
            } elseif ($item['answer'] === 'wrong') {
                $stats_arr[$item['user_id']][$item['template_detail_id']]['wrongs_count'] = $item['TOPLAM_VERI'];
            } elseif ($item['answer'] === 'null') {
                $stats_arr[$item['user_id']][$item['template_detail_id']]['nulls_count'] = $item['TOPLAM_VERI'];
            }
            if (!isset($this->booklets[$item['user_id']])) {
                $this->booklets[$item['user_id']] = $item['booklet'];
            }
        }

        return $stats_arr;
    }

    /**
     * @param array $stats_arr
     * @throws \Exception
     * @author selcukmart
     * 28.11.2021
     * 17:50
     */
    private function insert2DB(array $stats_arr): void
    {
        $OMRUserExamTemplateDetailStatsModelTable = OMRUserExamTemplateDetailStatsModel::table();
        $OMRUserExamStatsModelTable = OMRUserExamStatsModel::table();
        $exam_option_count = $this->getExamOptionCount();
        $this->getTemplateDetailData();

        foreach ($stats_arr as $user_id => $template_detail_ids) {
            $rights_count = 0;
            $wrongs_count = 0;
            $nulls_count = 0;
            $nets_count = 0;
            $control = [
                'user_id' => $user_id,
                'exam_id' => $this->exam_id,
            ];
            $score_total = 0;
            //c($template_detail_ids);
            foreach ($template_detail_ids as $template_detail_id => $stats) {
                $this_rights_count = isset($stats['rights_count']) ? $stats['rights_count'] : 0;
                $this_wrongs_count = isset($stats['wrongs_count']) ? $stats['wrongs_count'] : 0;
                $net = NetCalculate::result($exam_option_count, $this_rights_count, $this_wrongs_count);
                $score = $net * $this->template_details_data[$template_detail_id];
                $score_total += $score;
                $arr = [
                    'user_id' => $user_id,
                    'exam_id' => $this->exam_id,
                    'template_detail_id' => $template_detail_id,
                    'rights_count' => $this_rights_count,
                    'wrongs_count' => $this_wrongs_count,
                    'nulls_count' => isset($stats['nulls_count']) ? $stats['nulls_count'] : 0,
                    'net' => $net,
                    'score' => $score
                ];

                $nulls_count += $arr['nulls_count'];
                $wrongs_count += $arr['wrongs_count'];
                $rights_count += $arr['rights_count'];
                $nets_count += $net;
                $control['template_detail_id'] = $template_detail_id;
                $this->controlNInsert($control, $OMRUserExamTemplateDetailStatsModelTable, $arr);
                unset($control['template_detail_id']);
            }
            $score_total = call_user_func_array([$this->exam_type_class, 'getOverTotalScore'], [$score_total]);
            $arr = [
                'user_id' => $user_id,
                'exam_id' => $this->exam_id,
                'rights_count' => $rights_count,
                'wrongs_count' => $wrongs_count,
                'nulls_count' => $nulls_count,
                'net' => $nets_count,
                'score' => $score_total,
                'booklet' => $this->booklets[$user_id]
            ];

            $this->total_right_answers += $rights_count;
            $this->total_wrong_answers += $wrongs_count;
            $this->total_null_answers += $nulls_count;
            $this->total_net += $nets_count;
            $this->total_score += $score_total;

            $this->controlNInsert($control, $OMRUserExamStatsModelTable, $arr);
        }
        $this->examStatInsert();
        OpticalReaderMakeOrders::run($this->exam_id);
    }

    private function totalAllParticipants()
    {
        $vtable = OMRUserExamStatsModel::table();
        $sql = "SELECT
                        id
                    FROM $vtable
                    WHERE
                        exam_id='" . $this->exam_id . "'
                    ";
        $query = \DB::statement($sql);
        $this->total_all_participant = Db::row_count($query);
        $this->total_course_participant = $this->total_all_participant;
    }

    private function examStatInsert()
    {
        $this->totalAllParticipants();
        $arr = [
            'total_all_participant' => $this->total_all_participant,
            'exam_id' => $this->exam_id,
            'average_right_answers' => $this->divideByAllParticipats($this->total_right_answers),
            'average_wrong_answers' => $this->divideByAllParticipats($this->total_wrong_answers),
            'average_null_answers' => $this->divideByAllParticipats($this->total_null_answers),
            'average_score' => $this->divideByAllParticipats($this->total_score),
            'average_net' => $this->divideByAllParticipats($this->total_net),
            'total_course_participant' => $this->total_course_participant,
        ];
        $control = [
            'exam_id' => $this->exam_id,
        ];
        $table = OMRExamStatsModel::table();
        $result = Db::varlik_kontrolu($control, $table);
        if ($result) {
            Db::update($arr, $table, 'id', $result['id']);
        } else {
            Db::insert($arr, $table);
        }
    }

    private function divideByAllParticipats($a)
    {
        if ($this->total_all_participant <= 0) {
            return 0;
        }
        return $a / $this->total_all_participant;
    }

    /**
     * @param array $control
     * @param string $table
     * @param array $arr
     * @throws \Exception
     * @author selcukmart
     * 28.11.2021
     * 17:52
     */
    private function controlNInsert(array $control, string $table, array $arr)
    {
        $control_result = Db::varlik_kontrolu($control, $table);
        if ($control_result) {
            Db::update($arr, $table, 'id', $control_result['id'], 1);
        } else {
            Db::insert($arr, $table);
        }
        return $control_result;
    }

    public function __destruct()
    {

    }
}
