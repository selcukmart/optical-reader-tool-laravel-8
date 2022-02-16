<?php
/**
 * @author selcukmart
 * 27.01.2021
 * 23:19
 */

namespace FormGenerator\InputTypes;


use FormGenerator\FormGenerator;
use Sayfa\Template;

class StaticText implements InputTypeInterface
{
    use InputTypeTrait;

    public function __construct(FormGenerator $formGenerator)
    {
        $this->formGenerator = $formGenerator;
    }

    public function prepare(array $item)
    {
        $item ['template'] = 'STATIC_TEXT';
        $row_table = $this->formGenerator->getRowTable();
        $row_table['admin_url'] = ADMIN_URL;
        if (isset($item['content_callback']) && is_callable($item['content_callback'])) {
            $item['content'] = call_user_func_array($item['content_callback'], [$row_table, $item]);
        } else {
            $item['content'] = Template::smarty($row_table, $item['content']);
        }

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
