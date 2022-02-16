<?php
/**
 * @author selcukmart
 * 31.01.2021
 * 17:26
 */

namespace FormGenerator\InputTypes;


use Sayfa\Form\CodeGenerator;
use FormGenerator\FormGenerator;
use FormGenerator\Tools\Label;

class EqualityCode implements InputTypeInterface
{
    use InputTypeTrait;

    public function __construct(FormGenerator $formGenerator)
    {
        $this->formGenerator = $formGenerator;
    }

    public function prepare(array $item)
    {
        $row_table = $this->formGenerator->getRowTable();
        $kod = new CodeGenerator();
        if (!isset($item['label'])) {
            $item['label'] = 'Eşitlik Kodu';
        }
        $input = $kod->html($row_table);
        $this->label = new Label($item);
        $item['label'] = $this->label->getLabel();

        $this->unit_parts = [
            'input' => $input,
            'label' => $item['label'],
            'input_capsule_attributes' => '',
            'label_attributes' => ''
        ];

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
