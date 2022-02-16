<?php
/**
 * @author selcukmart
 * 21.11.2021
 * 18:19
 */

namespace OpticalMarkReader\OpticalReader;

use OpticalMarkReader\OpticalReader\OpticalReaderDBOperations\OpticalReaderQuestions;
use OpticalMarkReader\OpticalReader\OpticalReaderDBOperations\OpticalReaderUser;
use OpticalMarkReader\OpticalReader\OpticalReaderTools\DetectBooklets;
use OpticalMarkReader\OpticalReader\OpticalReaderTools\ExtractTXTFromTemplate;
use OpticalMarkReader\OpticalReader\OpticalReaderTools\OMRTemplateDetails;
use GlobalTraits\ErrorMessagesWithResultTrait;
use GlobalTraits\MessagesTrait;

class OpticalReaderManager
{
    use MessagesTrait;
    use ErrorMessagesWithResultTrait;

    private
        $valid = false,
        $exported_txt_data,
        $question_fields = [],
        $questions,
        $booklet = false,
        $booklet_field = '',
        $booklet_field_id = 0,
        $booklets = [],
        $exam_id,
        $template_details,
        $user_fields,
        $user_ids,
        $user_course_automation_number_field,
        $data;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->validate();
    }

    private function validate()
    {
        $this->validation = new OpticalReaderDataValidate($this);
        $this->valid = $this->validation->validate();
    }

    public function insert()
    {
        if ($this->isValid()) {

            $omr_template_detail = OMRTemplateDetails::instance($this);
            $omr_template_detail->get();

            $omr_export_txt = ExtractTXTFromTemplate::instance($this);
            $omr_export_txt->getExportedData();

            if ($this->isBooklet()) {
                $booklet_detection = DetectBooklets::instance($this);
                $booklet_detection->detect();
            }

            $question = OpticalReaderQuestions::instance($this);
            $question->insert();

            $user = OpticalReaderUser::instance($this);
            $user->insert();
            if (empty($this->getErrorMessage())) {
                $this->setResult(true);
            }

        }
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->valid;
    }

    /**
     * @param mixed $template_details
     */
    public function setTemplateDetails($template_details): void
    {
        $this->template_details = $template_details;
    }

    /**
     * @return mixed
     */
    public function getTemplateDetails()
    {
        return $this->template_details;
    }


    /**
     * @param mixed $exported_txt_data
     */
    public function setExportedTxtData($exported_txt_data): void
    {
        $this->exported_txt_data = $exported_txt_data;
    }

    /**
     * @return mixed
     */
    public function getExportedTxtData()
    {
        return $this->exported_txt_data;
    }

    /**
     * @return mixed
     */
    public function getExamId()
    {
        return $this->exam_id;
    }

    /**
     * @param mixed $exam_id
     */
    public function setExamId($exam_id): void
    {
        $this->exam_id = $exam_id;
    }

    /**
     * @return bool
     */
    public function isBooklet(): bool
    {
        return $this->booklet;
    }

    /**
     * @param bool $booklet
     */
    public function setBooklet(bool $booklet): void
    {
        $this->booklet = $booklet;
    }

    /**
     * @param string $booklet_field
     */
    public function setBookletField(string $booklet_field): void
    {
        $this->booklet_field = $booklet_field;
    }

    /**
     * @return string
     */
    public function getBookletField(): string
    {
        return $this->booklet_field;
    }

    /**
     * @param string $booklets
     */
    public function setBooklets($booklets): void
    {
        $this->booklets = $booklets;
    }

    /**
     * @return array
     */
    public function getBooklets(): array
    {
        return $this->booklets;
    }

    /**
     * @param string $question_fields
     */
    public function setQuestionFields($question_field_id, $question_field): void
    {
        $this->question_fields[$question_field_id] = $question_field;
    }

    /**
     * @return mixed
     */
    public function getQuestionFields()
    {
        return $this->question_fields;
    }

    /**
     * @param mixed $questions
     */
    public function setQuestions($questions): void
    {
        $this->questions = $questions;
    }

    /**
     * @return mixed
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * @return mixed
     */
    public function getUserFields()
    {
        return $this->user_fields;
    }

    /**
     * @return mixed
     */
    public function getUserCourseAutomationNumberField()
    {
        return $this->user_course_automation_number_field;
    }

    /**
     * @param mixed $user_fields
     */
    public function setUserFields($user_fields): void
    {
        $this->user_fields[] = $user_fields;
    }

    /**
     * @param mixed $user_course_automation_number_field
     */
    public function setUserCourseAutomationNumberField($user_course_automation_number_field): void
    {
        $this->user_course_automation_number_field = $user_course_automation_number_field;
    }

    /**
     * @param mixed $user_ids
     */
    public function setUserIds($user_ids): void
    {
        $this->user_ids = $user_ids;
    }

    /**
     * @param int $booklet_field_id
     */
    public function setBookletFieldId(int $booklet_field_id): void
    {
        $this->booklet_field_id = $booklet_field_id;
    }

    /**
     * @return int
     */
    public function getBookletFieldId(): int
    {
        return $this->booklet_field_id;
    }

    /**
     * @param array $data
     */
    public function add2Data($key,$value): void
    {
        $this->data[$key] = $value;
    }

    public function __destruct()
    {

    }
}
