<?php
/**
 * @author selcukmart
 * 30.01.2021
 * 17:33
 */

namespace FormGenerator\Export;


use HTML\Dom;
use FormGenerator\FormGenerator;
use FormGenerator\Tools\DependencyManagerV1;

class Bootstrapv3FormWizard
{
    private $formGenerator;

    public function __construct(FormGenerator $formGenerator)
    {
        $this->formGenerator = $formGenerator;
        $this->class_names = [];
        $this->filter = $this->formGenerator->getFilter();
        $this->formGenerator->sections = [];
        $this->formGenerator->tab_contents = [];

    }

    public function extract($items = null, $parent_group = null)
    {
        if (is_null($items)) {
            $items = $this->formGenerator->getInputs();
        }

        foreach ($items as $group => $item) {
            if (!is_null($parent_group)) {
                $item['group'] = $parent_group;
            }
            $item['input-id'] = $this->formGenerator->inputID($item);
            $will_filtered = $this->filter->willFiltered($item, $group);
            if ($will_filtered) {
                continue;
            }
            $this->is_string_group = !is_numeric($group) && is_string($group);
            if ($this->is_string_group && !isset($this->formGenerator->sections[$group])) {
                $group_label = '';
                if (isset($item[0]['label'])) {
                    $group_label = $item[0]['label'];
                }
                $this->formGenerator->sections[$group] = $group_label;
            }

            $this->extractCore($item, $group);
        }
    }


    private function extractCore($item, $group)
    {
        if ($this->is_string_group) {
            unset($item['input-id']);
            return $this->extract($item, $group);
        }
        if($item['type'] == 'form_section'){
            return '';
        }
        if (isset($this->class_names[$item['type']])) {
            $class_name = $this->class_names[$item['type']];
        } else {
            $class_name = \Classes::prepareFromString($item['type']);
            $this->class_names[$item['type']] = $class_name;
        }
        $class = $this->formGenerator->getClassPrefix() . $class_name;
        $run = new $class($this->formGenerator);
        $input_parts = $run->prepare($item);

        if (!isset($input_parts['input_capsule_attributes'])) {
            $input_parts['input_capsule_attributes'] = '';
        }

        $input_parts['input_capsule_attributes'] .= DependencyManagerV1::dependend($item);
        $arr = [
            'attributes' => [
                'id' => $item['input-id']
            ]
        ];
        $input_parts['input_capsule_attributes'] .= Dom::makeAttr($arr);
        if (isset($input_parts['template'])) {
            $template = $input_parts['template'];
        } else {
            $template = 'TEMPLATE';
        }

        if (!isset($this->formGenerator->tab_contents[$item['group']])) {
            $this->formGenerator->tab_contents[$item['group']] = [];
        }

        $this->formGenerator->tab_contents[$item['group']][] = $this->formGenerator->export($input_parts, $template);
    }

    public function __destruct()
    {

    }
}
