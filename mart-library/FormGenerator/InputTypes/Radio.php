<?php
/**
 * @author selcukmart
 * 24.01.2021
 * 11:37
 */

namespace FormGenerator\InputTypes;


use HTML\Dom;
use FormGenerator\FormGenerator;
use FormGenerator\Tools\DefaultValue;
use FormGenerator\Tools\Label;
use FormGenerator\Tools\Row;

class Radio implements InputTypeInterface
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
            'value' => '',
        ],
        'options' => '',
        'dont_set_id' => false,
        'value_callback' => ''
    ],
        $item = [],
        $row_data = [],
        $row = [],
        $units_output = '',
        $option_settings,
        $value;

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


        if ( isset($this->item['default_value']) && $this->item['default_value'] !== '') {
            $this->item['attributes']['value'] = $this->item['default_value'];
        }

        if (($this->item['attributes']['value']) === '') {
            global $vtable;
            $default_value = new DefaultValue($this->field, $vtable);
            $this->item['attributes']['value'] = $default_value->get();
        }

        $this->label = new Label($item);
        $this->item['label'] = $this->label->getLabel();

        $this->value = $this->item['attributes']['value'];


        $this->unit_parts = [
            'input' => $this->radioGenerate(),
            'label' => $this->item['label'],
            'input_capsule_attributes' => '',
            'label_attributes' => ''
        ];

        return $this->unit_parts;
    }

    private function radioGenerate()
    {
        $row = new Row($this->row_data);
        $row->setRow();
        $this->row = $row->getRow();
        $key = $this->option_settings['key'];
        $this->labelx = $this->option_settings['label'];

        foreach ($this->row as $index => $option_row) {

            $id = $this->field . '-' . $option_row[$key];
            $attr = [
                'type' => 'radio',
                'value' => $option_row[$key],
                'id' => $id,
                'name' => $this->field,
                //'class'=>'icheck'
            ];
            if ($this->value != '' && $option_row[$key] == $this->value) {
                $attr['checked'] = 'checked';
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
                'content' => ''
            ];


            $this->units_output .= '<label for="' . $id . '">' . Dom::generator($arr) . ' ' . ___($option_row[$this->labelx]) . '</label><br>';
        }
        //$this->units_output .= '</div>';
        return $this->units_output;
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
