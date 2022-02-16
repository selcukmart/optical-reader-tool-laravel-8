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

class MetadataCheckboxKontenjanKalan implements InputTypeInterface
{
    use InputTypeTrait;

    private
        $default_generator_arr = [
        'select_sql' => '',
        'dis_tip' => '',
        'kontenjan_kalan' => true,
        'only_checked' => false,
        'ext_conf' => []
    ];

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
        $sql = is_array($row_table) ? Template::embed_d($row_table, $sql) : $sql;
        $others = $item;
        unset($others['veri']);
        unset($others['sql']);
        $others = defaults($this->default_generator_arr, $others);

        $input = Metadata::set_checkbox_kontenjan_kalan($item['veri'], $veri_id, $sql, $others['dis_tip'], $others['select_sql'], $others['kontenjan_kalan'], $others['only_checked'], $others['ext_conf']);

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
