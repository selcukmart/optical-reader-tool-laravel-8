<?php
/**
 * @author selcukmart
 * 23.11.2021
 * 12:12
 */

namespace OpticalMarkReader\OpticalReader\OpticalReaderTools;

class DetectBooklets
{
    use OpticalReaderOperationTrait;

    public function detect()
    {
        $exported_data = $this->opticalReaderManager->getExportedTxtData();
        $booklet_field = $this->opticalReaderManager->getBookletField();
        $booklets = [];
        foreach ($exported_data as $exported_datum) {
            if (!in_array($exported_datum[$booklet_field], $booklets)) {
                $exported_datum[$booklet_field] = trim($exported_datum[$booklet_field]);
                if(empty($exported_datum[$booklet_field])){
                    continue;
                }
                $booklets[] = $exported_datum[$booklet_field];
            }
        }

        $this->opticalReaderManager->setBooklets($booklets);
    }
}