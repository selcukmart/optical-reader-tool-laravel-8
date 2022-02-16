<?php
/**
 * @author selcukmart
 * 13.02.2021
 * 11:47
 */

namespace FormGenerator\InputTypes;


use HTML\Dom;
use FormGenerator\FormGenerator;
use FormGenerator\Tools\DefaultValue;
use FormGenerator\Tools\Label;
use Sayfa\Template;

class Timezone implements InputTypeInterface
{
    use InputTypeTrait;

    private
        $formGenerator,
        $unit_parts = [],
        $default_generator_arr = [
        'default_value' => '',
        'attributes' => [
            'value' => '',
            'type' => 'select',
            'class' => 's2 form-control input-large',
        ],
        'dont_set_id' => false,
        'value_callback' => ''
    ],
        $item;

    public function __construct(FormGenerator $formGenerator)
    {
        $this->formGenerator = $formGenerator;
    }

    public function prepare(array $item)
    {
        $this->item = $item;
        $this->item = defaults($this->item, $this->default_generator_arr);

        $this->field = $this->item['attributes']['name'];

        $this->row_table = $this->formGenerator->getRowTable();

        if (isset($this->row_table[$this->field])) {
            $this->item['attributes']['value'] = $this->row_table[$this->field];

        }

        if ( isset($this->item['default_value']) && $this->item['default_value'] !== '') {
            $this->item['attributes']['value'] = $this->item['default_value'];
        }

        if (($this->item['attributes']['value']) === '') {
            global $vtable;
            $default_value = new DefaultValue($this->field , $vtable);
            $item['attributes']['value'] = $default_value->get();
        }

        $this->value = $this->item['attributes']['value'];

        $input_dom_array = [
            'element' => 'select',
            'attributes' => $this->item['attributes'],
            'content' => $this->optionGenerate()
        ];

        $this->label = new Label($item);
        $item['label'] = $this->label->getLabel();

        $this->unit_parts = [
            'input' => Dom::generator($input_dom_array),
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

    private function optionGenerate()
    {

        $this->options_array = timezone_identifiers_list();
        $this->options = '';

        foreach ($this->options_array as $key => $value) {
            $attr = [
                'value' => $value
            ];
            if ($this->value != '' && $value == $this->value) {
                $attr['selected'] = 'selected';
            }

            $option_label = $value;

            $arr = [
                'element' => 'option',
                'attributes' => $attr,
                'content' => $option_label
            ];
            $this->options .= Dom::generator($arr);
        }
        return $this->options;
    }

    public function __destruct()
    {

    }
}
