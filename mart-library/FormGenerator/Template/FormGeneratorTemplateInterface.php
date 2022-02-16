<?php
/**
 * @author selcukmart
 * 30.01.2021
 * 17:04
 */

namespace FormGenerator\Template;


use FormGenerator\FormGenerator;

interface FormGeneratorTemplateInterface
{
    public function __construct(FormGenerator $formGenerator);

    public function export($template = 'TEMPLATE', $return = false);

    public function setInputParts(array $input_parts): void;
}
