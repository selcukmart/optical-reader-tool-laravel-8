<?php
/**
 * @author selcukmart
 * 28.01.2021
 * 12:52
 */

namespace FormGenerator\InputTypes;


use KumeIliskileri;
use FormGenerator\FormGenerator;
use FormGenerator\Tools\Label;

class RecursiveSelect implements InputTypeInterface
{
    use InputTypeTrait;

    public function __construct(FormGenerator $formGenerator)
    {
        $this->formGenerator = $formGenerator;
    }

    public function prepare(array $item)
    {
        $row_table = $this->formGenerator->getRowTable();
        $item['veri']['selected'] = isset($row_table[$item['attributes']['name']]) ? $row_table[$item['attributes']['name']] : 0;
        $item['veri']['name'] = $item['attributes']['name'];
        $item['veri']['attributes'] = $item['attributes'];

        $KumeIliskileri = new KumeIliskileri();
        $input = $KumeIliskileri->rec_select($item['veri']);

        $this->label = new Label($item);
        $item['label'] = $this->label->getLabel();

        if (empty($item['veri']['placeholder'])) {
            $item['veri']['placeholder'] = $item['label'];
        }

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
