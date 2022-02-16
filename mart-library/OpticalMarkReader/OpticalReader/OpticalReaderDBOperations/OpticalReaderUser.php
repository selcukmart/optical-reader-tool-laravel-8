<?php
/**
 * @author selcukmart
 * 23.11.2021
 * 11:47
 */

namespace OpticalMarkReader\OpticalReader\OpticalReaderDBOperations;

use OpticalMarkReader\OpticalMarkReaderModels\OMRUsersModel;
use OpticalMarkReader\OpticalReader\OpticalReaderTools\OpticalReaderOperationTrait;


class OpticalReaderUser
{

    use OpticalReaderOperationTrait;

    const USER_FIELDS_FROM_TEMPLATES = [
        'course_automation_student_number' => 'course_automation_student_number',
        'identity_number' => 'identity_number',
        'name_surname' => 'name_surname',
        'phone_number' => 'phone_number',
        'mail' => 'mail',
    ];

    public function insert()
    {
        $exported_data = $this->opticalReaderManager->getExportedTxtData();
        /**
         * $booklets = Array
         * (
         * [0] => A
         * [1] => B
         * )
         */
        $booklets = $this->opticalReaderManager->getBooklets();

        $booklet_field_id = $this->opticalReaderManager->getBookletFieldId();
        $booklet_count = count($booklets);
        $count = count($exported_data);
        $arr = self::USER_FIELDS_FROM_TEMPLATES;
        $user_ids = [];
        $table = OMRUsersModel::table();
        $question_fields = $this->opticalReaderManager->getQuestionFields();
        $question_answers = $this->opticalReaderManager->getQuestions();
        /**
         * $data =>
         * [course_automation_student_number] => 00692
         * [6] => 00692
         * [Kitapçık] => B
         * [1] => B
         * [phone_number] =>
         * [7] =>
         * [name_surname] => HAVİN BOY EL
         * [8] => HAVİN BOY EL
         * [Türkçe] => EB CEABCDADDADDEC BDEDCC BDEAD
         * [2] => EB CEABCDADDADDEC BDEDCC BDEAD
         * [Sosyal Bilimler] => CDBECEDDCACAACB ABEDECAADAEBED
         * [3] => CDBECEDDCACAACB ABEDECAADAEBED
         * [Matematik] => CEBD AEA CED DBBBDCABDA BEDCAA
         * [4] => CEBD AEA CED DBBBDCABDA BEDCAA
         * [Fen Bilimleri] => CDBCEADDEBDBBECBCEBADECEBABECA
         * [5] => CDBCEADDEBDBBECBCEBADECEBABECA
         */
        $insert_user_answers = OpticalReaderUserAnswers::instance($this->opticalReaderManager);

        for ($i = $booklet_count; $i < $count; $i++) {

            if (isset($exported_data[$i]) && !empty($exported_data[$i])) {
                $data = $exported_data[$i];


                $insert = [];
                foreach ($arr as $table_field => $template_field) {
                    if (isset($data[$template_field])) {
                        if ($table_field === 'course_automation_student_number' || $table_field === 'identity_number') {
                            $insert[$table_field] = (int)trim((int)$data[$template_field]);
                        } else {
                            $insert[$table_field] = trim($data[$template_field]);
                        }

                    }
                }
                if (empty($insert)) {
                    continue;
                }

                $course_automation_student_number = isset($insert['course_automation_student_number']) ? $insert['course_automation_student_number'] : 0;
                $identity_number = isset($insert['identity_number']) ? $insert['identity_number'] : 0;

                $user_id = $this->detectUserID($course_automation_student_number, $identity_number);
                if (!$user_id || in_array($user_id, $user_ids)) {
                    $user_id = Db::insert($insert, $table);

                }
                $user_ids[] = $user_id;

                $booklet_name = '';
                if ($booklet_field_id) {
                    $booklet_name = trim($data[$booklet_field_id]);
                }

                /**
                 * $question_fields = Array
                 * (
                 * [2] => Türkçe
                 * [3] => Sosyal Bilimler
                 * [4] => Matematik
                 * [5] => Fen Bilimleri
                 * )
                 */
                foreach ($question_fields as $template_detail_id => $template_detail_name) {
                    if (!empty($booklet_name) && isset($data[$template_detail_id])) {
                        $question_answers_as_array = $question_answers[$booklet_name][$template_detail_id];
                        $user_answer_as_array = mb_str_split($data[$template_detail_id]);
                        $insert_user_answers->insert($user_id, $question_answers_as_array, $user_answer_as_array);
                    }
                }
            }
        }

        $this->opticalReaderManager->setUserIds($user_ids);
    }

    private function detectUserID($course_automation_student_number, $identity_number)
    {
        $table = OMRUsersModel::table();
        if (!empty($course_automation_student_number)) {
            $control = [
                'course_automation_student_number' => $course_automation_student_number
            ];
            $result = Db::varlik_kontrolu($control, $table);
            if ($result) {
                return $result['id'];
            }
        }

        if (!empty($identity_number)) {
            $control = [
                'identity_number' => $identity_number
            ];
            $result = Db::varlik_kontrolu($control, $table);
            if ($result) {
                return $result['id'];
            }
        }

        return false;
    }
}
