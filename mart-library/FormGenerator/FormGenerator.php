<?php
/**
 * @author selcukmart
 * 24.01.2021
 * 11:38
 */

namespace FormGenerator;


use HTML\Dom;
use FormGenerator\Export\Bootstrapv3Form;
use FormGenerator\Tools\DependencyManagerV1;
use FormGenerator\Tools\Filter;
use FormGenerator\Tools\Row;
use Sayfa\InputType;
use Str;

class FormGenerator
{
    protected
        $generator_array = [],
        $output = '',
        $error_message = '',
        $message = '',
        $export_format,
        $export_type,
        $row_table,
        $inputs,
        $has_filter = false,
        $input_filter = [],
        $input_excluding_filter = [],
        $input_group_filter = [],
        $input_group_excluding_filter = [],
        $scope,
        $filter,
        $namespace;


    public function __construct(array $generator_array, $scope)
    {
        $this->scope = $scope;
        $this->generator_array = $generator_array;
        $this->inputs = $this->generator_array['inputs'];
        $this->setRowTable();

        $this->export_format = $this->generator_array['export']['format'];
        $this->export_type = $this->generator_array['export']['type'];
        $this->filter = new Filter($this);
        $this->filter->hasFilter();
        $this->namespace = __NAMESPACE__;
        $this->class_prefix = $this->namespace . '\InputTypes\\';


    }


    /**
     * @param mixed $row_table
     */
    public function setRowTable(): void
    {
        $row_table_detection = new Row($this->generator_array);
        $row_table_detection->setRow();
        $this->row_table = $row_table_detection->getRow();
        if (empty($this->row_table)) {
            global $row_table;
            if ($row_table) {
                $this->row_table = $row_table;
            }
        }
    }

    /**
     * @return mixed
     */
    public function getRowTable()
    {
        return $this->row_table;
    }

    public function extract()
    {
        $class = $this->namespace . '\Export\\' . $this->export_format;

        $run = new $class($this);
        $run->extract();
    }

    public function export(array $input_parts, $template = 'TEMPLATE', $return = false)
    {
        $class = $this->namespace . '\Template\\' . $this->export_format;
        $run = new $class($this);
        $run->setInputParts($input_parts);

        return $run->export($template, $return);
    }

    public function inputID(&$item)
    {
        if (isset($item['attributes']['name'])) {
            return 'input-' . $item['attributes']['name'];
        }

        if (isset($item['label'])) {
            return 'input-' . Str::slug($item['label']);
        }
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->error_message;
    }

    /**
     * @return string
     */
    public function getOutput(): string
    {
        return $this->output;
    }

    /**
     * @param string $output
     */
    public function setOutput(string $output): void
    {
        $this->output .= $output;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @param string $error_message
     */
    public function setErrorMessage(string $error_message): void
    {
        $error_message = ' - ' . $error_message . '<br>';
        $this->error_message .= $error_message;
    }

    /**
     * @return mixed|string
     */
    public function getExportFormat(): string
    {
        return $this->export_format;
    }


    /**
     * @return mixed
     */
    public function getInputs()
    {
        return $this->inputs;
    }

    /**
     * @param bool $has_filter
     */
    public function setHasFilter(bool $has_filter): void
    {
        $this->has_filter = $has_filter;
    }

    /**
     * @param array $input_excluding_filter
     */
    public function setInputExcludingFilter(array $input_excluding_filter): void
    {
        $this->input_excluding_filter = $input_excluding_filter;
    }

    /**
     * @param array $input_filter
     */
    public function setInputFilter(array $input_filter): void
    {
        $this->input_filter = $input_filter;
    }

    /**
     * @param array $input_group_excluding_filter
     */
    public function setInputGroupExcludingFilter(array $input_group_excluding_filter): void
    {
        $this->input_group_excluding_filter = $input_group_excluding_filter;
    }

    /**
     * @param array $input_group_filter
     */
    public function setInputGroupFilter(array $input_group_filter): void
    {
        $this->input_group_filter = $input_group_filter;
    }

    /**
     * @return mixed
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @return array
     */
    public function getInputExcludingFilter(): array
    {
        return $this->input_excluding_filter;
    }

    /**
     * @return array
     */
    public function getInputFilter(): array
    {
        return $this->input_filter;
    }

    /**
     * @return array
     */
    public function getGeneratorArray(): array
    {
        return $this->generator_array;
    }

    /**
     * @return array
     */
    public function getInputGroupExcludingFilter(): array
    {
        return $this->input_group_excluding_filter;
    }

    /**
     * @return array
     */
    public function getInputGroupFilter(): array
    {
        return $this->input_group_filter;
    }

    /**
     * @return mixed
     */
    public function getExportType()
    {
        return $this->export_type;
    }

    /**
     * @return bool
     */
    public function isHasFilter(): bool
    {
        return $this->has_filter;
    }

    /**
     * @return Filter
     */
    public function getFilter(): Filter
    {
        return $this->filter;
    }

    /**
     * @return string
     */
    public function getClassPrefix(): string
    {
        return $this->class_prefix;
    }


    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function __destruct()
    {

    }
}
