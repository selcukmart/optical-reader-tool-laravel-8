<?php
/**
 * @author selcukmart
 * 23.11.2021
 * 10:54
 */

namespace OpticalMarkReader\OpticalReader\OpticalReaderTools;


use OpticalMarkReader\OpticalReader\OpticalReaderDBOperations\OpticalReaderUser;

class ExtractTXTFromTemplate
{
    use OpticalReaderOperationTrait;

    private
        $export = [],
        $content;

    public function getExportedData()
    {
        $this->data = $this->opticalReaderManager->getData();
        $this->content = $this->data['content'];//file_get_contents($_FILES['txt']['tmp_name']);
        $this->explodeRows = preg_split('/\r\n|\r|\n/', $this->content);
        $total = count($this->explodeRows);

        if ($total > 0) {
            $template_details = $this->opticalReaderManager->getTemplateDetails();

            foreach ($this->explodeRows as $index => $explodeRow) {
                $explodeRowx = trim($explodeRow);
                if (empty($explodeRowx)) {
                    continue;
                }

                //$split = str_split($explodeRow);
                $split = mb_str_split($explodeRow);
                //$split = preg_split('//u', $explodeRow, -1, PREG_SPLIT_NO_EMPTY);
                $this->export[$index] = [];

                foreach ($template_details as $template_detail) {
                    $user_field = false;
                    if (isset(OpticalReaderUser::USER_FIELDS_FROM_TEMPLATES[$template_detail['type']])) {
                        $user_field = true;
                        $this->export[$index][$template_detail['type']] = '';
                    }else{
                        $this->export[$index][$template_detail['name']] = '';
                    }
                    $this->export[$index][$template_detail['id']] = '';
                    for ($i = $template_detail['start_line']; $i <= $template_detail['finish_line']; $i++) {
                        if(!isset($split[$i])){
                            continue;
                        }
                        if ($user_field) {
                            $this->export[$index][$template_detail['type']] .= $split[$i];
                        }else{
                            $this->export[$index][$template_detail['name']] .= $split[$i];
                        }
                        $this->export[$index][$template_detail['id']] .= $split[$i];
                    }
                }

            }
        }
        $this->opticalReaderManager->setExportedTxtData($this->export);
        return $this->export;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return array
     */
    public function getExport(): array
    {
        return $this->export;
    }

}