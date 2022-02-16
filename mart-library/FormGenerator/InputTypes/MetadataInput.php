<?php
/**
 * @author selcukmart
 * 27.01.2021
 * 23:10
 */

namespace FormGenerator\InputTypes;


use FormGenerator\FormGenerator;
use FormGenerator\Tools\Label;

class MetadataInput implements InputTypeInterface
{
    use InputTypeTrait;

    public function __construct(FormGenerator $formGenerator)
    {
        $this->formGenerator = $formGenerator;
    }

    public function prepare(array $item)
    {
        $row_table = $this->formGenerator->getRowTable();
        $item['veri']['veri_id'] = isset($row_table['id']) ? $row_table['id'] : 0;

        $input = \Metadata\MetadataInput::set_input($item['veri']);

        if ($item['veri']['type'] != 'hidden') {
            $this->label = new Label($item);
            $item['label'] = $this->label->getLabel();
        } else {
            $item['label'] = 'hidden';
        }

        if (empty($item['veri']['placeholder'])) {
            $item['veri']['placeholder'] = $item['label'];
        }

        $this->unit_parts = [
            'input' => $input,
            'label' => $item['label'],
            'input_capsule_attributes' => '',
            'label_attributes' => ''
        ];

        if ($item['veri']['type'] == 'hidden') {
            $this->unit_parts['template'] = 'HIDDEN';
        }
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
