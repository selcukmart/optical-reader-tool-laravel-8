<?php
/**
 * @author selcukmart
 * 23.11.2021
 * 11:09
 */

namespace OpticalMarkReader\OpticalReader\OpticalReaderTools;

use OpticalMarkReader\OpticalMarkReaderModels\OMRTemplateDetailsModel;
use OpticalMarkReader\OpticalReader\OpticalReaderDBOperations\OpticalReaderUser;


class OMRTemplateDetails
{
    use OpticalReaderOperationTrait;

    public function get()
    {
        $arr = [
            'template_id' => $this->opticalReaderManager->getData()['template_id']
        ];
        $template_details = Db::fetch_all_by_arr($arr, OMRTemplateDetailsModel::table(), true, ' ORDER BY start_line ASC');
        $this->opticalReaderManager->setTemplateDetails($template_details);

        foreach ($template_details as $template_detail) {
            if ($template_detail['type'] === 'booklet') {
                $this->opticalReaderManager->setBooklet(true);
                $this->opticalReaderManager->setBookletField($template_detail['name']);
                $this->opticalReaderManager->setBookletFieldId($template_detail['id']);
            }

            if ($template_detail['type'] === 'question') {
                $this->opticalReaderManager->setQuestionFields($template_detail['id'], $template_detail['name']);
            }

            if ($template_detail['type'] === 'course_automation_student_number') {
                $this->opticalReaderManager->setUserCourseAutomationNumberField('course_automation_student_number');
            }

            if (isset(OpticalReaderUser::USER_FIELDS_FROM_TEMPLATES[$template_detail['type']])) {
                $this->opticalReaderManager->setUserFields($template_detail['type']);
            }
        }
    }
}
