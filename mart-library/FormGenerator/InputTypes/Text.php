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

class Text implements InputTypeInterface
{
    use InputTypeTrait;

    private
        $formGenerator,
        $unit_parts = [],
        $default_generator_arr = [
        'default_value' => '',
        'attributes' => [
            'value' => '',
            'type' => 'text',
            'class' => 'form-control input-large',
            'placeholder' => ''
        ],
        'dont_set_id' => false,
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

        if (!isset($item['attributes']['id'])) {
            if (!$item['dont_set_id']) {
                $item['attributes']['id'] = $item['attributes']['name'];
            }
        } else {
            if ($item['dont_set_id']) {
                unset($item['attributes']['id']);
            }
        }
        $row_table = $this->formGenerator->getRowTable();


        if (!empty($item['value_callback']) && is_callable($item['value_callback'])) {
            $item['attributes']['value'] = htmlspecialchars(call_user_func_array($item['value_callback'], [$row_table, $field]));
        } elseif (isset($row_table[$field])) {
            $item['attributes']['value'] = htmlspecialchars($row_table[$field]);
        }


        if ($item['attributes']['value'] == '' && isset($item['default_value']) && $item['default_value'] !== '') {
            $item['attributes']['value'] = $item['default_value'];
        }


        if ($item['attributes']['value'] === '') {
            global $vtable;
            $default_value = new DefaultValue($field, $vtable);
            $item['attributes']['value'] = $default_value->get();
        }

        $this->label = new Label($item);
        $item['label'] = $this->label->getLabel();

        if (empty($item['attributes']['placeholder'])) {
            $item['attributes']['placeholder'] = $this->label->getLabelWithoutHelp();
        }

        $input_dom_array = [
            'element' => 'input',
            'attributes' => $item['attributes'],
            'content' => ''
        ];

        $this->unit_parts = [
            'input' => Dom::generator($input_dom_array),
            'label' => $item['label'],
            'input_capsule_attributes' => '',
            'label_attributes' => ''
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
