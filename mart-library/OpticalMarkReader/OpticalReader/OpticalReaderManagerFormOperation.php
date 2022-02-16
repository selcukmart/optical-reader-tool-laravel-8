<?php
/**
 * @author selcukmart
 * 24.11.2021
 * 17:06
 */

namespace OpticalMarkReader\OpticalReader;

use GlobalTraits\ErrorMessagesWithResultTrait;

class OpticalReaderManagerFormOperation
{
    use ErrorMessagesWithResultTrait;

    private
        $post,
        $OpticalReaderManager,
        $exam_id,
        $convert_encoding = true;

    public function __construct(array $post, $file_path)
    {
        $post['content'] = file_get_contents($file_path);
        $this->post = $post;
    }


    public function insert()
    {
        $this->encodeConvert();
        $this->OpticalReaderManager = new OpticalReaderManager($this->post);
        $this->OpticalReaderManager->insert();
        if (!$this->OpticalReaderManager->isResult()) {
            $this->setErrorMessage($this->OpticalReaderManager->getErrorMessage());
        } else {
            $this->setResult($this->OpticalReaderManager->isResult());
        }
        return $this->isResult();
    }

    /**
     * @return mixed
     */
    public function getExamId()
    {
        return $this->OpticalReaderManager->getExamId();
    }

    private function encodeConvert()
    {
        if ($this->isConvertEncoding()) {
            $this->post['content'] = mb_convert_encoding($this->post['content'], 'UTF-8', 'ISO-8859-9');
        }
    }

    /**
     * @param bool $convert_encoding
     */
    public function setConvertEncoding(bool $convert_encoding): void
    {
        $this->convert_encoding = $convert_encoding;
    }

    /**
     * @return bool
     */
    public function isConvertEncoding(): bool
    {
        return $this->convert_encoding;
    }

    public function __destruct()
    {

    }
}
