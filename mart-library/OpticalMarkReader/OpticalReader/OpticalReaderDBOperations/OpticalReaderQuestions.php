<?php
/**
 * @author selcukmart
 * 23.11.2021
 * 11:50
 */

namespace OpticalMarkReader\OpticalReader\OpticalReaderDBOperations;

use OpticalMarkReader\OpticalMarkReaderModels\OMRQuestionsModel;
use OpticalMarkReader\OpticalReader\OpticalReaderTools\OpticalReaderOperationTrait;


class OpticalReaderQuestions
{
    use OpticalReaderOperationTrait;

    public function insert()
    {
        $booklets = $this->opticalReaderManager->getBooklets();
        $exported_data = $this->opticalReaderManager->getExportedTxtData();
        $question_fields = $this->opticalReaderManager->getQuestionFields();
        $question_lists = [];
        $exam_id = $this->opticalReaderManager->getExamId();
        $table = OMRQuestionsModel::table();

        foreach ($booklets as $a => $booklet) {
            $question_lists[$booklet] = [];
            foreach ($question_fields as $question_field_id => $question_field) {
                $question_lists[$booklet][$question_field_id] = [];
                $arr = [
                    'exam_id' => $exam_id,
                    'booklet' => $booklet,
                    'template_detail_id' => $question_field_id
                ];
                $questions = trim($exported_data[$a][$question_field]);
                $questions_split = str_split($questions);
                foreach ($questions_split as $index => $question) {
                    $arr['number'] = $index + 1;
                    $arr['right_anwser'] = $question;
                    $control = \DB::table($table)->where($arr)->limit(1)->first();
                    if (!$control) {
                        $arr['id'] = \DB::table($table)->insert($arr);
                    } else {
                        $arr['id'] = $control['id'];
                    }
                    $question_lists[$booklet][$question_field_id][$arr['number']] = $arr;
                    unset($arr['id']);
                }
            }
        }

        $this->opticalReaderManager->setQuestions($question_lists);
    }
}
