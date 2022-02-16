<?php
/**
 * @author selcukmart
 * 24.01.2021
 * 11:37
 */

namespace FormGenerator\InputTypes;


use HTML\Dom;
use FormGenerator\FormGenerator;
use FormGenerator\Tools\Label;

class Password implements InputTypeInterface
{
    use InputTypeTrait;

    private
        $formGenerator,
        $unit_parts = [],
        $default_generator_arr = [
        'attributes' => [
            'value' => '',
            'type' => 'password',
            'class' => 'form-control input-large',
            'placeholder' => ''
        ]
    ];

    public function __construct(FormGenerator $formGenerator)
    {
        $this->formGenerator = $formGenerator;
    }

    public function prepare(array $item)
    {
        $item = defaults($item, $this->default_generator_arr);

        if (!isset($item['attributes']['id'])) {
            $item['attributes']['id'] = $item['attributes']['name'];
        } else {
            if ($item['dont_set_id']) {
                unset($item['attributes']['id']);
            }
        }

        $item['attributes']['value'] = '';

        $this->label = new Label($item);
        $item['label'] = $this->label->getLabel();

        if (empty($item['attributes']['placeholder'])) {
            $item['attributes']['placeholder'] = $this->label->getLabelWithoutHelp();;
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
