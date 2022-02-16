<?php
/**
 * @author selcukmart
 * 13.02.2021
 * 12:21
 */

namespace FormGenerator\InputTypes;


use Localisation\LocalisationManager\LocalisationLanguageList;
use HTML\Dom;
use FormGenerator\FormGenerator;
use FormGenerator\Tools\DefaultValue;
use FormGenerator\Tools\Label;


class Language implements InputTypeInterface
{
    use InputTypeTrait;

    private
        $options_query,
        $formGenerator,
        $unit_parts = [],
        $default_generator_arr = [
        'default_value' => '',
        'attributes' => [
            'value' => '',
            'type' => 'select',
            'class' => 's2 form-control input-large',
        ],
        'options' => '',
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
            $default_value = new DefaultValue($this->field , $vtable);
            $item['attributes']['value'] = $default_value->get();
        }

        $this->value = $this->item['attributes']['value'];


        $input_dom_array = [
            'element' => 'select',
            'attributes' => $this->item['attributes'],
            'content' => $this->optionGenerate()
        ];

        $this->label = new Label($this->item);
        $this->item['label'] = $this->label->getLabel();

        $this->unit_parts = [
            'input' => Dom::generator($input_dom_array),
            'label' => $this->item['label'],
            'input_capsule_attributes' => '',
            'label_attributes' => ''
        ];


        return $this->unit_parts;
    }

    private function optionGenerate()
    {
        $sql = "SELECT * FROM " . LocalisationLanguageList::table() . " ORDER BY code ASC";
        $this->options_query = \DB::statement($sql);
        $this->options = '';

        //c($this->options_array);

        foreach ($this->options_query as $option_row) {
            $attr = [
                'value' => $option_row['code']
            ];
            if ($this->value != '' && $option_row['code'] == $this->value) {
                $attr['selected'] = 'selected';
            }

            $option_label = ___($option_row['language']) . ' - ' . $option_row['code'];

            $arr = [
                'element' => 'option',
                'attributes' => $attr,
                'content' => $option_label
            ];
            $this->options .= Dom::generator($arr);
        }
        return $this->options;
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
