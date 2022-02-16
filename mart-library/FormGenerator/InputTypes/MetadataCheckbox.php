<?php
/**
 * @author selcukmart
 * 28.01.2021
 * 13:09
 */

namespace FormGenerator\InputTypes;


use Metadata\Metadata;
use FormGenerator\FormGenerator;
use FormGenerator\Tools\DependencyManagerV1;
use FormGenerator\Tools\Label;
use Sayfa\Template;

class MetadataCheckbox implements InputTypeInterface
{
    use InputTypeTrait;

    public function __construct(FormGenerator $formGenerator)
    {
        $this->formGenerator = $formGenerator;
    }

    public function prepare(array $item)
    {
        $row_table = $this->formGenerator->getRowTable();
        $veri_id = isset($row_table['id']) ? $row_table['id'] : 0;
        global $vtable_as;
        $vtable_as = 'xyz';
        $sql = $item['sql'];

        $select_sql = isset($item['select_sql']) ? $item['select_sql'] : '';

        $sql = is_array($row_table) ? Template::embed_d($row_table, $sql) : $sql;
        $input = Metadata::set_checkbox($item['veri'], $veri_id, $sql, $select_sql);

        $this->label = new Label($item);
        $item['label'] = $this->label->getLabel();

        $this->unit_parts = [
            'input' => '<div style="max-height:200px; overflow:auto">' . $input . '</div>',
            'label' => $item['label'],
            'input_capsule_attributes' => '',
            'label_attributes' => '',
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
