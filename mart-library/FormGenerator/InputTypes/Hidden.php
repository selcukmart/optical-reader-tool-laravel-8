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

class Hidden implements InputTypeInterface
{
    use InputTypeTrait;

    private
        $formGenerator,
        $unit_parts = [],
        $default_generator_arr = [
        'default_value' => '',
        'attributes' => [
            'value' => '',
            'type' => 'hidden',
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

        if (!isset($this->item['attributes']['id'])) {
            $this->item['attributes']['id'] = $this->item['attributes']['name'];
        }

        $row_table = $this->formGenerator->getRowTable();


        if (!empty($this->item['value_callback']) && is_callable($this->item['value_callback'])) {
            $this->item['attributes']['value'] = htmlspecialchars(call_user_func_array($this->item['value_callback'], [$row_table, $this->field]));
        } else {
            $this->item['attributes']['value'] = isset($row_table[$this->field]) ?? htmlspecialchars($row_table[$this->field]);
        }

        if ( isset($this->item['default_value']) && $this->item['default_value'] !== '') {
            $this->item['attributes']['value'] = $this->item['default_value'];
        }

        if (($this->item['attributes']['value']) === '') {
            global $vtable;
            $default_value = new DefaultValue($this->field , $vtable);
            $item['attributes']['value'] = $default_value->get();
        }

        $input_dom_array = [
            'element' => 'input',
            'attributes' => $this->item['attributes'],
            'content' => ''
        ];
        $this->unit_parts = [
            'input' => Dom::generator($input_dom_array),
            'template' => 'HIDDEN'
        ];

        return $this->unit_parts;
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
