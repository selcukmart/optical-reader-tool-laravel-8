<?php
/**
 * @author selcukmart
 * 24.01.2021
 * 14:23
 */

namespace FormGenerator\Template;


use FormGenerator\FormGenerator;
use Sayfa\Template;
use Smarty;

class Bootstrapv3Form implements FormGeneratorTemplateInterface
{
    use FormGeneratorTemplateTrait;

    const FORM_SECTION = '<h4 class="form-section"   style="text-transform: capitalize;margin-bottom: 0px; margin-top: 20px;">{$label}</h4>';
    const LABEL_DESC = '<small><em>{$label_desc}</em></small>';
    const LABEL = '<label class="control-label" {$label_attributes}>{$label}</label>';
    const HIDDEN = '{$input}';
    const HELP_BLOCK = '<span class="help-block"> {$help_block} </span>';
    const TEMPLATE = '<div class="form-group {$form_group_class}" {$input_capsule_attributes}>
                    <label style="max-height:200px; overflow:auto" class="control-label col-md-3" {$label_attributes}>{$label}</label>
                    {$label_desc}
                    <div class="col-md-9" style="max-height:600px; overflow:auto">
                    {$input_above_desc}
                    {$input}
                    {$input_belove_desc}
                    </div>
                </div>';
    const DATETIMEPICKER__INPUT_TEMPLATE = '<div class="input-inline input-large">
                    <div class="input-group">
                            <input {$attributes} placeholder="{$placeholder}" autocomplete="false" name="{$name}" type="text"
                                   class="form-control input-large datetimepicker_here"
                                   value="{$value}"
                                   size="32">
                            <div class="input-group-addon datetimepicker_here_btn" style="cursor:pointer"><i
                                        class="fa fa-calendar"></i></div>
                    </div>
                </div>';

    const DATE_RANGE__INPUT_TEMPLATE = '<div class="input-inline input-large">
                    <div class="input-group">
                            <input autocomplete="false" name="{$name}" type="text"
                                   class="form-control input-large date_range_here"
                                   value="{$value}"
                                   size="32">
                            <div class="input-group-addon date_range_here_btn" style="cursor:pointer"><i
                                        class="fa fa-calendar"></i></div>
                    </div>
                </div>';

    const STATIC_TEXT = '<div class="form-section" style="margin-top: -15px;">
                                                    <p class="form-control-static"> {$content} </p>
                                                </div>';
    const IMAGE = '
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
   ';

    const FILE = '
    {$previous_file}
  <div class="fileinput fileinput-new" data-provides="fileinput">
    <div class="input-group input-medium">
      <div class="form-control uneditable-input input-fixed input-medium" data-trigger="fileinput"> <i class="fa fa-file fileinput-exists"></i>&nbsp;
      <span class="fileinput-filename"> </span> </div>
      <span class="input-group-addon btn default btn-file"> <span class="fileinput-new"> Seç </span> <span class="fileinput-exists"> Değiştir </span>
      <input name="{$name}" id="{$name}" {$input_attr} type="file">
      </span> <a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Sil </a> </div>
      </div>';

    private
        $formGenerator,
        $input_parts = [];


    private $input_variables = [
        'form_group_class' => '',
        'label_desc' => '',
        'input_above_desc' => '',
        'label' => '',
        'attributes' => '',
        'input_attr' =>''
    ];

    public function export($template = 'TEMPLATE', $return = false)
    {
        if (empty($this->input_parts)) {
            $this->formGenerator->setErrorMessage('Input Verilmedi');
        } else {
            $output = $this->embedTemplate($template);
            if ($return) {
                return $output;
            }
            $this->formGenerator->setOutput($output);
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
