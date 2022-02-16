<?php
/**
 * @author selcukmart
 * 15.04.2021
 * 17:33
 */

namespace FormGenerator\Export;


use HTML\Dom;

use FormGenerator\FormGenerator;
use FormGenerator\Tools\DependencyManagerV1;


class Bootstrapv4FormFrontend
{
    private $formGenerator;

    public function __construct(FormGenerator $formGenerator)
    {
        $this->formGenerator = $formGenerator;
        $this->class_names = [];
        $this->filter = $this->formGenerator->getFilter();

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
            $this->extractCore($item, $group);
        }
    }

    private function extractCore($item, $group)
    {
        if (!is_numeric($group) && is_string($group)) {
            unset($item['input-id']);
            return $this->extract($item, $group);
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
        $help_block = '';
        if (isset($item['help_block']) && !empty($item['help_block'])) {
            $item['help_block'] = ___($item['help_block']);
            $help_block = $this->formGenerator->export($item, 'HELP_BLOCK', true);
        }
        if (!isset($input_parts['input_belove_desc'])) {
            $input_parts['input_belove_desc'] = '';
        }
        $input_parts['input_belove_desc'] .= $help_block;
        $this->formGenerator->export($input_parts, $template);
    }


    public function __destruct()
    {

    }
}
