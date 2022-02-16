<?php
/**
 * @author selcukmart
 * 27.01.2021
 * 22:59
 */

namespace FormGenerator\InputTypes;


use FormGenerator\FormGenerator;
use FormGenerator\Tools\Label;

class FormSection implements InputTypeInterface
{
    use InputTypeTrait;

    public function __construct(FormGenerator $formGenerator)
    {
        $this->formGenerator = $formGenerator;
    }

    public function prepare(array $item)
    {

        $item ['template'] = 'FORM_SECTION';
        $this->label = new Label($item);
        $item['label'] = $this->label->getLabel();
        $this->unit_parts = $item;
        return $this->unit_parts;
    }

    public function getUnitParts(): array
    {
        return $this->unit_parts;
    }

    public function __destruct()
    {

    }
}
