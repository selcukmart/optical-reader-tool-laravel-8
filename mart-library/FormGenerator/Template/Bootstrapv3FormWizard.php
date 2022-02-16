<?php
/**
 * @author selcukmart
 * 30.01.2021
 * 17:04
 */

namespace FormGenerator\Template;


use FormGenerator\FormGenerator;
use Sayfa\Template;

class Bootstrapv3FormWizard implements FormGeneratorTemplateInterface
{
    use FormGeneratorTemplateTrait;

    private
        $formGenerator,
        $input_parts = [];

    const FORM_SECTION = '<h4 class="form-section">{$label}</h4>';
    const HIDDEN = '{$input}';
    const LABEL = '<label class="control-label" {$label_attributes}>{$label}</label>';
    const DATETIMEPICKER__INPUT_TEMPLATE = '<div class="input-inline input-large">
                    <div class="input-group">
                            <input autocomplete="false" name="{$name}" type="text"
                                   class="form-control input-large datetimepicker_here"
                                   value="{$value}"
                                   size="32">
                            <div class="input-group-addon datetimepicker_here_btn" style="cursor:pointer"><i
                                        class="fa fa-calendar"></i></div>
                    </div>
                </div>';

    const DATE_RANGE__INPUT_TEMPLATE = '<div class="input-inline input-large">
                    <div class="input-group">
                            <input {$attributes} placeholder="{$placeholder}" autocomplete="false" name="{$name}" type="text"
                                   class="form-control input-large date_range_here"
                                   value="{$value}"
                                   size="32">
                            <div class="input-group-addon date_range_here_btn" style="cursor:pointer"><i
                                        class="fa fa-calendar"></i></div>
                    </div>
                </div>';

    const TEMPLATE = '<div class="form-group {$form_group_class}" {$input_capsule_attributes}>
                    <label class="control-label" {$label_attributes}>{$label}</label><br>
                    {$label_desc}

                    {$input_above_desc}
                    {$input}
                    {$input_belove_desc}

                </div>';

    const STATIC_TEXT = '<div class="form-group">
                                                    <label>{$label}</label>
                                                    <p class="form-control-static"> {$content} </p>
                                                </div>';

    const IMAGE = '<div class="form-group">
    <label class="control-label">{$label} </label>
    <div >
      <div class="fileinput fileinput-new" data-provides="fileinput">
        <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;"> <img src="{$src}" alt=""> </div>
        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 10px;"> </div>
        <div>
        <span class="btn default btn-file">
            <span class="fileinput-new"> Foto Seç </span>
            <span class="fileinput-exists"> Değiştir</span>
            <input type="hidden">
            <input name="{$name}" id="{$name}" type="file">
        </span>
        <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> Sil </a> </div>
      </div>
      <div class="clearfix margin-top-10"><span class="label label-danger">Not!</span> Yüklenmiş imaj thumbnaili ancak IE10+, FF3.6+, Safari6.0+, Chrome6.0+ and Opera11.1+ sürümlerinde  gösterilebilir. </div>
    </div>
  </div>';

    private $input_variables = [
        'form_group_class' => '',
        'attributes' => '',
        'label_attributes' => '',
        'input_belove_desc' => '',
        'label_desc' => '',
        'input_above_desc' => '',
        'label' => ''
    ];

    public function export($template = 'TEMPLATE', $return = false)
    {
        if (empty($this->input_parts)) {
            $this->formGenerator->setErrorMessage('Input Verilmedi');
        } else {
            $output = $this->embedTemplate($template);
            return $output;
        }

    }

    /**
     * @param array $input_parts
     */
    public function setInputParts(array $input_parts): void
    {
        $this->input_parts = $input_parts;
    }

    public function __destruct()
    {

    }
}
