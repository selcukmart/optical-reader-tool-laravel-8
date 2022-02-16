<?php
/**
 * @author selcukmart
 * 4.12.2021
 * 14:09
 */

namespace OpticalMarkReader\OpticalReaderStats\Charts\Combochart;

use OpticalMarkReader\OpticalMarkReaderModels\OMRExamsModel;
use Sayfa\JS;
use GlobalTraits\ErrorMessagesWithResultTrait;


class OpticalReaderUserExamStatsCombochart
{
    use ErrorMessagesWithResultTrait;

    private
        $user_id,
        $last_exam_id,
        $exam_data,
        $provided_by_as_name,
        $provided_by;

    public function __construct(int $user_id, $last_exam_id)
    {
        $this->user_id = $user_id;
        $this->last_exam_id = $last_exam_id;
        $this->exam_data = Db::verisi($this->last_exam_id, OMRExamsModel::table());

    }

    public function getData()
    {

        if (!is_null($this->provided_by)) {
            $name = $this->provided_by_as_name;

            if (!isset($this->{$name}) || is_null($this->{$name})) {
                $this->{$name} = new $this->provided_by($this);
            }

            return $this->{$name}->getData($this->by);
        }
    }

    /**
     * @param mixed $provided_by
     */
    public function setProvidedBy($provided_by): void
    {
        $provideded_by_exp = explode('_', $provided_by);
        $this->by = strtolower($provideded_by_exp[0]);
        unset($provideded_by_exp[0]);
        $this->provided_by_as_name = implode('_', $provideded_by_exp);

        $classname = 'CombochartBy' . \Classes::prepareClassName($this->provided_by_as_name);
        $classname = __NAMESPACE__ . '\CombochartStatProviders\\' . $classname;
        if (class_exists($classname)) {
            $this->provided_by = $classname;
        } else {
            $this->setErrorMessage('Maalesef istatistik datasını nasıl üreteceğimizi tespit edemedik.');
        }

    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * @return mixed
     */
    public function getLastExamId()
    {
        return $this->last_exam_id;
    }

    /**
     * @return mixed
     */
    public function getExamData()
    {
        return $this->exam_data;
    }

    public static function getChartData($user_id, $last_exam_id, $provideded_by)
    {
        global $combochart;
        if (is_null($combochart)) {
            JS::register_array(
                [
                    CONTENT_URL . "/themes/frontend/v1/assets/plugins/chart_helpers.js",
                    CONTENT_URL . "/themes/frontend/v1/assets/plugins/student-report/charts.js",
                ]
            );
            $combochart = new self($user_id, $last_exam_id);
        }
        $combochart->setProvidedBy($provideded_by);
        return json_encode($combochart->getData());
    }

    public function __destruct()
    {

    }
}
