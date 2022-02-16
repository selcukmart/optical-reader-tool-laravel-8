<?php
/**
 * @author selcukmart
 * 24.01.2021
 * 14:49
 */

namespace FormGenerator\InputTypes;


use FormGenerator\FormGenerator;

interface InputTypeInterface
{
    public function __construct(FormGenerator $formGenerator);

    public function prepare(array $item);

    public function getUnitParts(): array;

    public function __destruct();
}
