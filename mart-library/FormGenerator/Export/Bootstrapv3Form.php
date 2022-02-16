<?php
/**
 * @author selcukmart
 * 30.01.2021
 * 17:33
 */

namespace FormGenerator\Export;



use FormGenerator\FormGenerator;



class Bootstrapv3Form
{
    use FormGeneratorExportTrait;

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
        //c($items);
        foreach ($items as $group => $item) {
            if (!is_null($parent_group)) {
                $item['group'] = $parent_group;
            }

            if (isset($item['type'], $item['label']) && $item['type'] == 'form_section' && empty($item['label'])) {
                continue;
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

        $this->prepareInputParts($item);
        $this->prepareTemplate();

        $this->formGenerator->export($this->input_parts, $this->template);
    }


    public function __destruct()
    {

    }
}
