<?php
/**
 * @author selcukmart
 * 2.12.2021
 * 11:36
 */

namespace OpticalMarkReader\OpticalReaderStats\UserExamReportTemplateDetailStatsArray;



class Generic
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

        $total = Db::row_count($this->query);
        $divide = ceil($total/3);
        $a = 0;
        $b = 0;
        foreach ($this->query as $item) {
            if ($a > $divide) {
                $b++;
                $a = 0;
            }

            $this->template_detail_stats[$b][] = $item;
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
