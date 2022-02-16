<?php
/**
 * @author selcukmart
 * 24.01.2021
 * 11:37
 */

namespace FormGenerator\InputTypes;


use HTML\Dom;
use FormGenerator\FormGenerator;
use FormGenerator\Tools\DefaultValue;
use FormGenerator\Tools\Label;
use FormGenerator\Tools\Row;
use Sayfa\Modalv2;
use Sayfa\Template;
use Sistem\EncV2;

class Select implements InputTypeInterface
{
    use InputTypeTrait;

    private
        $options,
        $formGenerator,
        $unit_parts = [],
        $default_generator_arr = [
        'default_value' => '',
        'empty_option' => true,
        'translate_option' => false,
        'attributes' => [
            'value' => '',
            'type' => 'select',
            'class' => 's2 form-control input-large',
        ],
        'options' => '',
        'option_settings' => [
            'key' => 'id',
            'label' => 'isim'
        ],
        'dont_set_id' => false,
        'value_callback' => '',
        'add_button_at_same_place' => [
            'set_button' => false,
            'add_button_options' => [
                'icon' => 'plus-square-o',
                'a_class' => 'btn blue-hoki btn-xs btn-outline sbold uppercase ',
            ],
        ],
    ],
        $item,
        $option_settings;

    public function __construct(FormGenerator $formGenerator)
    {
        $this->formGenerator = $formGenerator;
    }

    public function prepare(array $item)
    {
        $this->item = $item;

        $this->options_data = $this->item['options'];
        $this->item = defaults($this->item, $this->default_generator_arr);
        $this->translate_option = $this->item['translate_option'];
        $this->option_settings = $this->item['option_settings'];
        $this->field = $this->item['attributes']['name'];

        if (!isset($this->item['attributes']['id'])) {
            if (!$this->item['dont_set_id']) {
                $this->item['attributes']['id'] = $this->item['attributes']['name'];
            }
        } else {
            if ($this->item['dont_set_id']) {
                unset($this->item['attributes']['id']);
            }
        }

        $this->row_table = $this->formGenerator->getRowTable();

        if (isset($this->row_table[$this->field])) {
            $this->item['attributes']['value'] = $this->row_table[$this->field];

        }


        if (isset($this->item['default_value']) && $this->item['default_value'] !== '') {
            $this->item['attributes']['value'] = $this->item['default_value'];
        }

        if (($this->item['attributes']['value']) === '') {
            global $vtable;
            $default_value = new DefaultValue($this->field, $vtable);
            $item['attributes']['value'] = $default_value->get();
        }

        $this->value = $this->item['attributes']['value'];


        $input_dom_array = [
            'element' => 'select',
            'attributes' => $this->item['attributes'],
            'content' => $this->optionGenerate()
        ];

        $this->label = new Label($this->item);
        $this->item['label'] = $this->label->getLabel();

        $this->unit_parts = [
            'input' => Dom::generator($input_dom_array) . $this->addButton(),
            'label' => $this->item['label'],
            'input_capsule_attributes' => '',
            'label_attributes' => ''
        ];


        return $this->unit_parts;
    }

    private function addButton()
    {
        if ($this->hasAddButton()) {
            $arr = $this->item['add_button_at_same_place']['add_button_options'];
            if (!isset($arr['title'])) {
                $arr['title'] = $this->item['label'];
            }
            $options = [
                'object' => $this->formGenerator,
                'item' => $this->item
            ];
            $arr['fields']['data-select'] = EncV2::eQueryString(serialize($options));
            $arr['fields']['data-starter-input-id'] = $this->item['attributes']['id'];
            $arr['fields']['data-operation'] = 'add-button-operation';
            $arr['fields']['data-url'] = $this->item['add_button_at_same_place']['add-url'];
            $arr['fields']['style'] = "line-height:24px;";

            return Modalv2::linki($arr);
        }
    }

    private function optionGenerate()
    {

        $row = new Row($this->options_data);
        $row->setRow();
        $this->options_array = $row->getRow();
        $this->options = '';
        if ($this->item['empty_option']) {
            $this->options .= '<option value="">...</option>';
        }

        $key = $this->option_settings['key'];
        $this->label = $this->option_settings['label'];

        if (!$this->options_array) {
            return '';
        }
        foreach ($this->options_array as $option_row) {
            $attr = [
                'value' => $option_row[$key]
            ];
            if ($this->value != '' && $option_row[$key] == $this->value) {
                $attr['selected'] = 'selected';
            }

            if (isset($this->options_data['label'])) {
                $option_label = Template::smarty($option_row, $this->options_data['label']);
            } else {
                $option_label = $option_row[$this->label];
            }
            if ($this->translate_option) {
                $option_label = ___($option_label);
            }
            $arr = [
                'element' => 'option',
                'attributes' => $attr,
                'content' => $option_label
            ];
            $this->options .= Dom::generator($arr);
        }
        return $this->options;
    }

    /**
     * @return array
     */
    public function getUnitParts(): array
    {
        return $this->unit_parts;
    }

    public function __destruct()
    {

    }

    /**
     * @return mixed
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return bool
     * @author selcukmart
     * 27.11.2021
     * 14:55
     */
    private function hasAddButton(): bool
    {
        return isset($this->item['add_button_at_same_place']['set_button']) && $this->item['add_button_at_same_place']['set_button'];
    }
}
