<?php
/**
 * @author selcukmart
 * 24.01.2021
 * 16:15
 */

namespace FormGenerator\InputTypes;


use FormGenerator\FormGenerator;
use FormGenerator\Tools\DefaultValue;
use FormGenerator\Tools\Label;
use Zaman;

class DateRange implements InputTypeInterface
{
    use InputTypeTrait;

    private
        $formGenerator,
        $unit_parts = [],
        $default_generator_arr = [
        'default_value' => '',
        'attributes' => [
            'value' => '',
            'placeholder' => ''
        ],
        'value_callback' => ''
    ];

    public function __construct(FormGenerator $formGenerator)
    {
        $this->formGenerator = $formGenerator;
    }

    public function prepare(array $item)
    {
        $this->item = $item;
        $this->item = defaults($this->item, $this->default_generator_arr);
        $this->field = $this->item['attributes']['name'];


        $row_table = $this->formGenerator->getRowTable();

        if (isset($row_table[$this->field])) {
            $this->item['attributes']['value'] = Zaman::set_day_time($row_table[$this->field]);

        }

        if ( isset($this->item['default_value']) && $this->item['default_value'] !== '') {
            $this->item['attributes']['value'] = $this->item['default_value'];
        }

        if (($this->item['attributes']['value']) === '') {
            global $vtable;
            $default_value = new DefaultValue($this->field , $vtable);
            $item['attributes']['value'] = $default_value->get();
        }

        $this->label = new Label($this->item);
        $this->item['label'] = $this->label->getLabel();
        if (empty($this->item['attributes']['placeholder'])) {
            $this->item['attributes']['placeholder'] = $this->label->getLabelWithoutHelp();
        }
        $this->item['attributes']['name'] .= '[]';
        $this->item['attributes']['placeholder'] = 'Başlama';
        $input1 = $this->formGenerator->export($this->item['attributes'], 'DATETIMEPICKER__INPUT_TEMPLATE', true);
        $this->item['attributes']['placeholder'] = 'Bitiş';
        $input2 = $this->formGenerator->export($this->item['attributes'], 'DATETIMEPICKER__INPUT_TEMPLATE', true);
//        $label1 = $this->formGenerator->export([
//            'label' => 'Başlama'
//        ], 'LABEL', true);
//        $label2 = $this->formGenerator->export([
//            'label' => 'Bitiş'
//        ], 'LABEL', true);

        $this->unit_parts = [
            'input' => $input1 . '<hr>' . $input2,
            'label' => $this->item['label'],
            'input_capsule_attributes' => '',
            'label_attributes' => ''
        ];

        return $this->unit_parts;
    }

    public function getUnitParts(): array
    {
        return $this->unit_parts;
    }

    public function __destruct()
    {

    }


}
