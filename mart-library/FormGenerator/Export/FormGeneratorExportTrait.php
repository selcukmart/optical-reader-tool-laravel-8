<?php


namespace FormGenerator\Export;


use HTML\Dom;
use FormGenerator\Tools\DependencyManagerV1;

trait FormGeneratorExportTrait
{
    private
        $docInjectionManager,
        $input_parts,
        $template,
        $without_help_block = ['hidden', 'static_text', 'file'];

    private function getHelpBlock($item)
    {
        $str = '';
        if (!in_array($item['type'], $this->without_help_block)) {
            if (isset($item['help_block']) && !empty($item['help_block'])) {
                $item['help_block'] = ___($item['help_block']);
                $str = $this->formGenerator->export($item, 'HELP_BLOCK', true);
            }

//            $this->docInjectionManager = $this->run->getUserGuideInjector();
//            return $this->docInjectionManager->renderHelp('input', $str);
        }
        return $str;

    }

    private function prepareInputParts($item)
    {
        if (isset($this->class_names[$item['type']])) {
            $class_name = $this->class_names[$item['type']];
        } else {
            $class_name = \Classes::prepareFromString($item['type']);
            $this->class_names[$item['type']] = $class_name;
        }

        $class = $this->formGenerator->getClassPrefix() . $class_name;
        $this->run = new $class($this->formGenerator);
        $this->input_parts = $this->run->prepare($item);


        $help_block = $this->getHelpBlock($item);
        if (!isset($this->input_parts['input_belove_desc'])) {
            $this->input_parts['input_belove_desc'] = '';
        }

        $this->input_parts['input_belove_desc'] .= $help_block;

        if (!isset($this->input_parts['input_capsule_attributes'])) {
            $this->input_parts['input_capsule_attributes'] = '';
        }

        $this->input_parts['input_capsule_attributes'] .= DependencyManagerV1::dependend($item);

        $arr = [
            'attributes' => [
                'id' => $item['input-id']
            ]
        ];

        $this->input_parts['input_capsule_attributes'] .= Dom::makeAttr($arr);

        return $this->input_parts;
    }

    private function prepareTemplate()
    {
        if (isset($this->input_parts['template'])) {
            $this->template = $this->input_parts['template'];
        } else {
            $this->template = 'TEMPLATE';
        }

        return $this->template;
    }
}
