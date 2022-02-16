<?php
/**
 * @author selcukmart
 * 24.01.2021
 * 14:52
 */

namespace FormGenerator\InputTypes;


use HTML\Dom;
use FormGenerator\FormGenerator;
use FormGenerator\Tools\Label;

class Generic implements InputTypeInterface
{
    use InputTypeTrait;

    private
        $formGenerator,
        $unit_parts = [],
        $default_generator_arr = [
        'output' => '',
        'dont_set_id' => false,
    ];

    public function __construct(FormGenerator $formGenerator)
    {
        $this->formGenerator = $formGenerator;
    }

    public function prepare(array $item)
    {
        $item = defaults($item, $this->default_generator_arr);
        $this->label = new Label($item);
        $item['label'] = $this->label->getLabel();

        $this->unit_parts = [
            'input' => $item['output'],
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
