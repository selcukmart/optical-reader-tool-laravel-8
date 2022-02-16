<?php
/**
 * getUserGuideInjector çekmek için
 *
 * @author Uğur Biçer <ugurbicer@onlinekurum.com>
 * @date 10.06.2021
 */

namespace FormGenerator\InputTypes;


use FormGenerator\Tools\Label;
use UserGuide\UserGuideFormInjector\UserGuideFormInjector;

trait InputTypeTrait
{
    private $label;

    public function getUserGuideInjector(): UserGuideFormInjector
    {
        $return =  $this->label->getUserGuideFormInjector();

        return $return;
    }
}
