<?php
/**
 * @author selcukmart
 * 31.01.2021
 * 15:14
 */

namespace FormGenerator\InputTypes;


use Kume\Klasor;
use Metadata\AdvancedRecursiveFrom;
use Nesne\EgitimPaketleri;
use FormGenerator\FormGenerator;
use FormGenerator\Tools\Label;

class AdvancedRecursiveForm implements InputTypeInterface
{
    use InputTypeTrait;

    public function __construct(FormGenerator $formGenerator)
    {
        $this->formGenerator = $formGenerator;
    }

    public function prepare(array $item)
    {
        $row_table = $this->formGenerator->getRowTable();
        if (!isset($item['foreign_key'])) {
            $foreign_key = 'veri2_id';
            $this_key = 'veri_id';
        }

        if (isset($item['foreign_key'])) {
            if ($item['foreign_key'] == 'veri2_id') {
                $foreign_key = 'veri2_id';
                $this_key = 'veri_id';
            } else {
                $foreign_key = 'veri_id';
                $this_key = 'veri2_id';
            }
        }

        $item['veri']['foreign_key'] = $foreign_key;
        $item['veri']['this_key'] = $this_key;

        $item['veri'][$this_key] = (isset($row_table['id'])) ? $row_table['id'] : 0;
        if (isset($item['attributes'])) {
            $item['veri']['attributes'] = $item['attributes'];
        }
        $Metadata = new AdvancedRecursiveFrom();


        $input = $Metadata->recursive($item['veri']);

        $this->label = new Label($item);
        $item['label'] = $this->label->getLabel();

        if (empty($item['veri']['placeholder'])) {
            $item['veri']['placeholder'] = $this->label->getLabelWithoutHelp();
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
