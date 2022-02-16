<?php
/**
 * @author selcukmart
 * 2.12.2021
 * 11:36
 */

namespace OpticalMarkReader\OpticalReaderStats\UserExamReportTemplateDetailStatsArray;



class TYT
{
    private
        $query,
        $template_detail_stats;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function getArray()
    {
        if (!is_null($this->template_detail_stats)) {
            return $this->template_detail_stats;
        }

        $a = 0;
        foreach ($this->query as $item) {
            if ($a == 0) {
                $this->template_detail_stats[0][] = $item;
            } elseif ($a > 0 && $a <= 5) {
                $this->template_detail_stats[1][] = $item;
            } else {
                $this->template_detail_stats[2][] = $item;
            }
            $a++;
        }
        return $this->template_detail_stats;
    }

    /**
     * @return mixed
     */
    public function getTemplateDetailStats()
    {
        $this->getArray();
        return $this->template_detail_stats;
    }

    public function __destruct()
    {

    }
}
