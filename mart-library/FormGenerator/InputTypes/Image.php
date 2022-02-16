<?php
/**
 * @author selcukmart
 * 25.01.2021
 * 20:26
 */

namespace FormGenerator\InputTypes;


use Foto\Foto;
use FormGenerator\FormGenerator;
use FormGenerator\Tools\Label;

class Image implements InputTypeInterface
{
    use InputTypeTrait;

    public function __construct(FormGenerator $formGenerator)
    {
        $this->formGenerator = $formGenerator;
    }

    public function prepare(array $item)
    {
        $this->row_table = $this->formGenerator->getRowTable();
        $src = ADMIN_TEMPLATE_URL . '/assets/img/izleme-logo.png';

        if (isset($this->row_table['foto'])) {
            $kapak = Foto::analiz($this->row_table['foto'], 'o');
            if (is_object($kapak)) {
                $src = $kapak->orta;

            }
        }
        $this->label = new Label($item);
        $item['attributes']['src'] = $src;
        $this->unit_parts = [
            'input' => $this->formGenerator->export($item['attributes'],'IMAGE',true),
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
