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

class Ckeditor implements InputTypeInterface
{
    use InputTypeTrait;

    private
        $formGenerator,
        $unit_parts = [],
        $default_generator_arr = [
        'default_value' => '',
        'attributes' => [
            'value' => '',
            'type' => 'textarea',
            'class' => 'ckfinder col-sm-6 form-control input-large',
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
        $row_table = $this->formGenerator->getRowTable();

        if (!empty($this->item['value_callback']) && is_callable($this->item['value_callback'])) {
            $this->item['attributes']['value'] = htmlspecialchars(call_user_func_array($this->item['value_callback'], [$row_table, $this->field]));
        } elseif(isset($row_table[$this->field])){
                $this->item['attributes']['value'] = htmlspecialchars($row_table[$this->field]);

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

        $value = $this->item['attributes']['value'];

        unset($this->item['attributes']['value']);

        $input_dom_array = [
            'element' => 'textarea',
            'attributes' => $this->item['attributes'],
            'content' => $value
        ];


        $this->unit_parts = [
            'input' => Dom::generator($input_dom_array),
            'label' => $this->item['label'],
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
