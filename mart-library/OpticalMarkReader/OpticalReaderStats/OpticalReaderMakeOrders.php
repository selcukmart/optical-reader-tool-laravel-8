<?php
/**
 * @author selcukmart
 * 2.12.2021
 * 10:18
 */

namespace OpticalMarkReader\OpticalReaderStats;

use OpticalMarkReader\OpticalMarkReaderModels\OMRUserExamStatsModel;


class OpticalReaderMakeOrders
{
    private $exam_id;

    public function __construct(int $exam_id)
    {
        $this->exam_id = $exam_id;
    }

    public function order()
    {
        $table = OMRUserExamStatsModel::table();
        $sql = "SELECT
            *
        FROM " . $table . "
        WHERE
            exam_id='" . $this->exam_id . "'
        ORDER BY score DESC";
        $query = \DB::statement($sql);
        $a = 0;
        foreach ($query as $item) {
            $a++;
            $arr = [
                'course_order' => $a,
                'general_order' => $a
            ];
            Db::update($arr, $table, 'id', $item['id']);
        }
    }

    public static function run(int $exam_id)
    {
        $self =  new self($exam_id);
        $self->order();
        return $self;
    }

    public function __destruct()
    {

    }
}
