<?php
/**
 * @author selcukmart
 * 7.06.2021
 * 11:26
 */

namespace FormGenerator\Template;


use FormGenerator\FormGenerator;

trait FormGeneratorTemplateTrait
{
    public function __construct(FormGenerator $formGenerator)
    {
        $this->formGenerator = $formGenerator;
    }

    private function embedTemplate($template)
    {
        $smarty = new \Smarty();
        $this->input_parts = defaults($this->input_parts, $this->input_variables);
        foreach ($this->input_parts as $index => $input_part) {
            $smarty->assign($index, $input_part);
        }

        $template = constant(__CLASS__ . '::' . $template);
        $output = $smarty->fetch("eval:$template");
        return $output;
    }
}
