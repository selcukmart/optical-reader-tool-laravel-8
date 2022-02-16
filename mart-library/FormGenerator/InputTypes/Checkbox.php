<?php
/**
 * @author selcukmart
 * 24.01.2021
 * 11:37
 */

namespace FormGenerator\InputTypes;


use HTML\Dom;
use FormGenerator\FormGenerator;
use FormGenerator\Tools\CheckedControl;
use FormGenerator\Tools\DefaultValue;
use FormGenerator\Tools\Label;
use FormGenerator\Tools\Row;

class Checkbox implements InputTypeInterface
{
    use InputTypeTrait;

    private
        $formGenerator,
        $unit_parts = [],
        $default_generator_arr = [
        'default_value' => '',
        'option_settings' => [
            'key' => 'id',
            'label' => 'isim'
        ],
        'attributes' => [
        ],
        'options' => '',
        'dont_set_id' => false,
        'value_callback' => ''
    ],
        $item = [],
        $row_data = [],
        $row = [],
        $units_output = '';

    public function __construct(FormGenerator $formGenerator)
    {
        $this->formGenerator = $formGenerator;
    }

    public function prepare(array $item)
    {
        $this->item = $item;
        $this->row_data = $this->item['options'];
        $this->item = defaults($this->item, $this->default_generator_arr);
        $this->option_settings = $this->item['option_settings'];
        $this->field = $this->item['attributes']['name'];

        if (!isset($this->item['attributes']['id'])) {
            if (!$this->item['dont_set_id']) {
                $this->item['attributes']['id'] = $this->item['attributes']['name'];
            }
        } else {
            if ($this->item['dont_set_id']) {
                unset($this->item['attributes']['id']);
            }
        }

        $this->row_table = $this->formGenerator->getRowTable();

        if (isset($this->row_table[$this->field])) {
            $this->item['attributes']['value'] = $this->row_table[$this->field];

        }


        if (isset($this->item['default_value']) && $this->item['default_value'] !== '') {
            $this->item['attributes']['value'] = $this->item['default_value'];
        }

        if (isset($this->item['attributes']['value']) && ($this->item['attributes']['value']) === '') {
            global $vtable;
            $default_value = new DefaultValue($this->field, $vtable);
            $item['attributes']['value'] = $default_value->get();
        }

        $this->label = new Label($item);
        $item['label'] = $this->label->getLabel();

        $this->unit_parts = [
            'input' => $this->checkboxGenerate(),
            'label' => $this->item['label'],
            'input_capsule_attributes' => '',
            'label_attributes' => ''
        ];

        return $this->unit_parts;
    }

    private function checkboxGenerate()
    {
        $row = new Row($this->row_data);
        $row->setRow();
        $this->row = $row->getRow();
        $key = $this->option_settings['key'];
        $this->label = $this->option_settings['label'];
        $checkeds = [];
        $checked_control = new CheckedControl($this->item['options']['control'], $this->row_table);
        foreach ($this->row as $index => $option_row) {

            $id = $this->field . '-' . $option_row[$key];
            $attr = [
                'type' => 'checkbox',
                'value' => $option_row[$key],
                'id' => $id,
                'name' => $this->field . '[]'
            ];

            $attr['label'] = $option_row[$this->label];
            $checked_control->control($option_row[$key]);
            if ($checked_control->isChecked()) {
                $attr['checked'] = 'checked';
                $checkeds[$option_row[$key]] = $attr;
                $checkeds[$option_row[$key]]['id'] = $option_row['id'];
            }

            if (isset($this->item['dependency']) && $this->item['dependency']) {
                $arr = [
                    'data-dependency' => 'true',
                    'data-dependency-group' => $this->field,
                    'data-dependency-field' => $id
                ];
                $attr = array_merge($attr, $arr);
            }

            $arr = [
                'element' => 'input',
                'attributes' => $attr,
                'content' => $this->label
            ];
            $this->units_output .= '<label for="' . $id . '">' . Dom::generator($arr) . ' ' . $option_row[$this->label] . '</label><br>';
        }
        $checkeds_str = '';
        if (sizeof($checkeds)) {
            $checkeds_str .= '
<div class="well well-sm">
<div class="label label-default">Öncesinde Seçilenler</div><br><br>
            ';
            foreach ($checkeds as $index => $checked) {
                $checkeds_str .= '<span class="badge badge-success">' . $checked['label'] . ' ' . $checked['id'] . '</span> ';
            }
            $checkeds_str .= '<div></div></div>';
        }
        return $checkeds_str . $this->units_output;
    }

    /**
     * @return array
     */
    public function getUnitParts(): array
    {
        return $this->unit_parts;
    }

    public function __destruct()
    {

    }
}
