<?php
/**
 * @author selcukmart
 * 3.12.2021
 * 12:08
 */

namespace OpticalMarkReader\OpticalReaderStats;

use OpticalMarkReader\OpticalMarkReaderModels\OMRUserExamStatsModel;
use OpticalMarkReader\OpticalMarkReaderModels\OMRUsersModel;
use GlobalTraits\ErrorMessagesWithResultTrait;


class OpticalReaderSearch
{
    use ErrorMessagesWithResultTrait;

    private
        $exams,
        $table,
        $huv_id,
        $user_id,
        $code;

    public function __construct()
    {
        $this->table = OMRUsersModel::table();
    }

    /**
     * @param array|string $code
     */
    public function setCode($code): void
    {
        $this->code = $code;
        $this->code = clean_get_signs($this->code);
    }

    /**
     * @param mixed $huv_id
     */
    public function setHuvId($huv_id): void
    {
        $this->huv_id = $huv_id;
    }

    public function search()
    {
        if ($this->user_id) {
            $this->getExamsOverUser($this->user_id);
            return;
        }
        $this->code =(int) trim((int) $this->code);
        if (!empty($this->code)) {
            $control = $this->getOverIdentityNumber($this->code);
            if ($control) {
                $this->getExamsOverUser($control['id']);
            } else {
                $control = $this->courseAutomationStudentNumber($this->code);
                if (!$control) {
                    $this->setErrorMessage('Bu koda ait bir kullanıcı bulunamadı!');
                } else {
                    $this->getExamsOverUser($control['id']);
                }
            }
            return;
        }

        if ($this->huv_id) {
            $control = $this->courseAutomationStudentNumber($this->huv_id);
            if (!$control) {
                $this->setErrorMessage('Bu koda ait bir kullanıcı bulunamadı!');
            } else {
                $this->getExamsOverUser($control['id']);
            }
        } else {
            $this->setErrorMessage('Lütfen kodunuzu giriniz!');
        }
    }

    public function getExams()
    {

        if (empty($this->user_id)) {
            return false;
        }
        if (!is_null($this->exams)) {
            return $this->exams;
        }
        $arr = [
            'user_id' => $this->user_id
        ];
        $this->exams = Db::fetch_all_by_arr($arr, OMRUserExamStatsModel::table(), $filter = true, $order = ' ORDER BY id DESC');
        if (!count($this->exams)) {
            $this->setErrorMessage('Bu kullanıcıya ait bir sınav bulunamadı');
        } else {
            $this->setResult(true);
        }
        return $this->exams;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    private function courseAutomationStudentNumber($parameter)
    {
        $arr = [
            'course_automation_student_number' => $parameter
        ];

        return Db::varlik_kontrolu($arr, $this->table);
    }

    /**
     * @return mixed
     * @author selcukmart
     * 6.12.2021
     * 15:31
     */
    private function getOverIdentityNumber($parameter)
    {
        $arr = [
            'identity_number' => $parameter
        ];
        return Db::varlik_kontrolu($arr, $this->table);
    }

    /**
     * @param $id
     * @author selcukmart
     * 6.12.2021
     * 15:34
     */
    private function getExamsOverUser($id): void
    {
        $this->user_id = $id;
        $this->getExams();
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id): void
    {
        $this->user_id = $user_id;
    }

    public function __destruct()
    {

    }
}
