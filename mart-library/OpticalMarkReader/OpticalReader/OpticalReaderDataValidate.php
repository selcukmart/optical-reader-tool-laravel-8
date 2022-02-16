<?php
/**
 * @author selcukmart
 * 21.11.2021
 * 18:20
 */

namespace OpticalMarkReader\OpticalReader;

use OpticalMarkReader\OpticalMarkReaderModels\OMRTemplateDetailsModel;
use OpticalMarkReader\OpticalMarkReaderModels\OMRTemplatesModel;
use OpticalMarkReader\OpticalReader\OpticalReaderDBOperations\OpticalReaderExam;


class OpticalReaderDataValidate
{
    private
        $data,
        $opticalReaderManager;

    public function __construct(OpticalReaderManager $opticalReaderManager)
    {
        $this->opticalReaderManager = $opticalReaderManager;
        $this->data = $this->opticalReaderManager->getData();
    }

    public function validate()
    {
        $result = $this->examNameValidation();
        if ($result) {
            $result = $this->templateIDValidate();
        }

        if ($result) {
            $result = $this->txtFileValidation();
        }


        return $result;
    }

    private function examNameValidation()
    {
        if (!isset($this->data['exam_id'])) {
            $this->opticalReaderManager->setErrorMessage('Sınav Gönderilmelidir!');
            return false;
        }

        if (isset($this->data['exam_id'])) {
            $this->data['exam_id'] = trim($this->data['exam_id']);
            if (empty($this->data['exam_id'])) {
                $this->opticalReaderManager->setErrorMessage('Sınav Gönderilmelidir!');
                return false;
            }
        }

        $exam = OpticalReaderExam::instance($this->opticalReaderManager);
        $exam->getVariables();
        $this->data = $this->opticalReaderManager->getData();
        return true;
    }

    private function txtFileValidation()
    {

//        if (!isset($_FILES['txt']['tmp_name'])) {
//            $this->opticalReaderManager->setErrorMessage('Optik Form Okuma Dosyası Gönderilmelidir.');
//            return false;
//        }
//
//        if (!empty($_FILES['txt']['error'])) {
//            $this->opticalReaderManager->setErrorMessage('Optik Form Okuma Dosyası okunurken hata oldu.');
//            return false;
//        }
//
//        if (!file_exists($_FILES['txt']['tmp_name'])) {
//            $this->opticalReaderManager->setErrorMessage('Optik Form Okuma Dosyası Geçici Dosyası Bulunamadı.');
//            return false;
//        }

        return true;
    }

    private function templateIDValidate()
    {
        if (!isset($this->data['template_id'])) {
            $this->opticalReaderManager->setErrorMessage('Optik Form Şablonu Gönderilmelidir.');
            return false;
        }

        $this->data['template_id'] = (int)$this->data['template_id'];
        if (empty($this->data['template_id'])) {
            $this->opticalReaderManager->setErrorMessage('Optik Form Şablonu Seçilmelidir.');
            return false;
        }

        $template = Db::verisi($this->data['template_id'], OMRTemplatesModel::table());
        if (!$template) {
            $this->opticalReaderManager->setErrorMessage('Optik Form Şablonu Sistemde Tanımlı Değil.');
            return false;
        }


        $template_details = Db::varlik_kontrolu([
            'template_id' => $this->data['template_id']
        ], OMRTemplateDetailsModel::table());
        if (!$template_details) {
            $this->opticalReaderManager->setErrorMessage('Optik Form Şablonu Sistemde Doğru Yapılandırılmamış.');
            return false;
        }

        return true;
    }

    public function __destruct()
    {

    }
}
