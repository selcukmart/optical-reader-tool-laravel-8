<?php
/**
 * @author selcukmart
 * 24.01.2021
 * 16:15
 */

namespace FormGenerator\InputTypes;


use FormGenerator\FormGenerator;
use FormGenerator\Tools\Label;
use Zaman;

class Datetimepicker implements InputTypeInterface
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
        $item = defaults($item, $this->default_generator_arr);
        $field = $item['attributes']['name'];


        $row_table = $this->formGenerator->getRowTable();

        if (isset($row_table[$field])) {
            $item['attributes']['value'] = Zaman::set_day_time($row_table[$field]);

        }

        if ($item['attributes']['value'] == '' && isset($item['default_value']) && $item['default_value'] !== '') {
            $item['attributes']['value'] = $item['default_value'];
        }

        $this->label = new Label($item);
        $item['label'] = $this->label->getLabel();
        if (empty($item['attributes']['placeholder'])) {
            $item['attributes']['placeholder'] = $this->label->getLabelWithoutHelp();;
        }

        $this->unit_parts = [
            'input' => $this->formGenerator->export($item['attributes'], 'DATETIMEPICKER__INPUT_TEMPLATE', true),
            'label' => $item['label'],
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
