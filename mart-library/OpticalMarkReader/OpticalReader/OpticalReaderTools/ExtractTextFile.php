<?php
/**
 * @author selcukmart
 * 21.11.2021
 * 14:14
 */

namespace OpticalMarkReader\OpticalReader\OpticalReaderTools;

class ExtractTextFile
{
    private
        $explodeRows = [],
        $column_detect_array = [],
        $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public function getArray()
    {
        $this->explodeRows = preg_split('/\r\n|\r|\n/', $this->content);
        return $this->detectColumnStarts();
    }

    private function detectColumnStarts()
    {
        $total = count($this->explodeRows);
        if ($total > 0) {
            foreach ($this->explodeRows as $explodeRow) {
                $first_item = mb_str_split($explodeRow);
                $a = 0;
                $before_full = false;
                foreach ($first_item as $x) {
                    $this->columnDetect($x, $a, $before_full);
                    $a++;
                }
            }
            ksort($this->column_detect_array);
        }

        return $this->column_detect_array;
    }

    /**
     * @param $x
     * @param int $a
     * @author selcukmart
     * 21.11.2021
     * 15:09
     */
    private function columnDetect($x, int $a, &$before_full): void
    {
        $x = trim($x);
        if ($x !== '') {
            if (!$before_full) {
                /**
                 * doluluk başlama noktası
                 */
                $this->column_detect_array[$a] = [
                    'start' => $a,
                    'column' => $x,
                    'finish' => $a
                ];
                $before_full = $a;
            } else {
                $this->column_detect_array[$before_full]['column'] .= $x;
                $this->column_detect_array[$before_full]['finish'] = $a;
            }
        } else {
            $before_full = false;
        }
    }

    public function __destruct()
    {

    }
}
