<?php
/**
 * @author selcukmart
 * 23.11.2021
 * 11:50
 */

namespace OpticalMarkReader\OpticalReader\OpticalReaderDBOperations;

use OpticalMarkReader\OpticalMarkReaderModels\OMRUserAnswersModel;
use OpticalMarkReader\OpticalReader\OpticalReaderTools\OpticalReaderOperationTrait;


class OpticalReaderUserAnswers
{
    use OpticalReaderOperationTrait;



    public function insert($user_id, $exam_answers, $user_answers)
    {
        $table = OMRUserAnswersModel::table();
        foreach ($exam_answers as $index => $exam_answer) {
            $answer_char = trim(isset($user_answers[$index - 1]) ? $user_answers[$index - 1] : '');
            if (empty($answer_char)) {
                $answer = 'null';
            } else {
                $answer = ($answer_char === $exam_answer['right_anwser']) ? 'right' : 'wrong';
            }
            $arr = [
                'user_id' => $user_id,
                'exam_id' => $exam_answer['exam_id'],
                'booklet' => $exam_answer['booklet'],
                'template_detail_id' => $exam_answer['template_detail_id'],
                'question_id' => $exam_answer['id'],
            ];
            $is_exists_in_db = Db::varlik_kontrolu($arr, $table);
            if ($is_exists_in_db) {
                $upd = [
                    'answer_char' => $answer_char,
                    'right_answer_char' => $exam_answer['right_anwser'],
                    'answer' => $answer,
                ];
                Db::update($upd, $table, 'id', $is_exists_in_db['id'], 1);
            } else {
                $arr = [
                    'user_id' => $user_id,
                    'exam_id' => $exam_answer['exam_id'],
                    'booklet' => $exam_answer['booklet'],
                    'template_detail_id' => $exam_answer['template_detail_id'],
                    'question_id' => $exam_answer['id'],
                    'answer_char' => $answer_char,
                    'right_answer_char' => $exam_answer['right_anwser'],
                    'answer' => $answer,
                ];
                Db::insert($arr, $table);
            }

        }
    }
}
