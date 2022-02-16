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
use FormGenerator\Tools\Row;
use Sayfa\Template;


class Currency implements InputTypeInterface
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


        if (isset($this->item['default_value']) && $this->item['default_value'] !== '') {
            $this->item['attributes']['value'] = $this->item['default_value'];
        }

        if (($this->item['attributes']['value']) === '') {
            global $vtable;
            $default_value = new DefaultValue($this->field, $vtable);
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

    private function optionGenerate()
    {
        $sql = "SELECT * FROM " . \Localisation\LocalisationManager\Currency::table() . " ORDER BY currency ASC";
        $this->options_query = \DB::statement($sql);
        $this->options = '';
        foreach ($this->options_query as $option_row) {
            $attr = [
                'value' => $option_row['currency']
            ];
            if ($this->value != '' && $option_row['currency'] == $this->value) {
                $attr['selected'] = 'selected';
            }

            $option_label = $option_row['currency'] . ' - ' . ___($option_row['currency_desc']);

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
