<?php
/**
 * @author selcukmart
 * 13.02.2021
 * 16:55
 */

namespace FormGenerator\Tools;




class DefaultValue
{
    private
        $all_field_options = [],
        $field = '';

    public function __construct(string $field, $table = NULL)
    {
        if (!is_null($table)) {
            $this->all_field_options = Db::get_all_field_options($table);
            $this->field = $field;
        }
    }

    public function get()
    {
        if (isset($this->all_field_options[$this->field])) {
            if ($this->all_field_options[$this->field]['Default'] !== '') {
                return $this->all_field_options[$this->field]['Default'];
            }
        }
        return '';
    }

    public function __destruct()
    {

    }
}
