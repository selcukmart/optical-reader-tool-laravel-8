<?php
/**
 * @author selcukmart
 * 25.01.2021
 * 20:26
 */

namespace FormGenerator\InputTypes;


use FormGenerator\FormGenerator;
use FormGenerator\Tools\Label;
use Sayfa\Sayfa\Mix;

class File implements InputTypeInterface
{
    use InputTypeTrait;

    public function __construct(FormGenerator $formGenerator)
    {
        $this->formGenerator = $formGenerator;
    }

    public function prepare(array $item)
    {
        $this->row_table = $this->formGenerator->getRowTable();
        $previous_file = '';
        $name = $item['attributes']['name'];
        if (!isset($item['attributes']['id'])) {
            $item['attributes']['id'] = $name;
        }

        $this->label = new Label($item);

        if (isset($this->row_table[$name])) {
            $previous_file = $this->row_table[$name];
        }

        if (!empty($previous_file)) {
            $link = $previous_file;
            if (isset($item['link_hash'])) {
                $link_hash = $item['link_hash'];
            }else{
                $link_hash = md5($link);
            }

            $previous_file = '<div class="row" id="' . $link_hash . '"><div class="col-md-4">
            <a class="btn green-haze btn-outline sbold uppercase btn-block" target="__blank" href="' . $link . '">
            <i class="fa fa-download"></i> İndir</a></div>';

            $previous_file .= '<div class="col-md-4"><a class="btn green-haze btn-outline sbold uppercase btn-block"
            href="javascript:;" onClick="javascript:pencere(\'' . \Sayfa\Mix\Mix::google_docs_link($link) . '\',950,800);">
             <i class="fa fa-external-link"></i> Görüntüle</a> </div>';

            $attr = '';

            if (isset($item['attributes']['id'])) {
                $attr = ' data-id="' . $item['attributes']['id'] . '" ';
            } elseif (isset($item['attributes']['tip'], $item['attributes']['tip2'], $item['attributes']['veri_id'], $item['attributes']['veri2_id'])) {
                $restriction = [
                    'tip',
                    'tip2',
                    'tip3',
                    'veri_id',
                    'veri2_id',
                    'veri3_id',
                ];
                foreach ($item['attributes'] as $key => $value) {
                    if (in_array($key, $restriction)) {
                        $attr .= ' data-' . $key . '="' . $value . '"';
                    }
                }

            }

            $previous_file .= '<div class="col-md-4"><a class="btn red-mint btn-outline sbold uppercase btn-block
            delete-document" ' . $attr . ' data-file-url="' . $link . '" href="javascript:;"> <i class="fa fa-trash"></i> Sil</a> </div></div>';

            $previous_file .= '<div class="alert alert-info" style="display: none" id="delete-alert-' . $link_hash . '">
            <button type="button" class="close" data-dismiss="alert"></button>
            <h4 class="alert-heading"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Dosya Silinmiştir</h4>
            </div> <hr>';

        }


        $item['attributes']['previous_file'] = $previous_file;

        $this->unit_parts = [
            'input' => $this->formGenerator->export($item['attributes'], 'FILE', true),
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
