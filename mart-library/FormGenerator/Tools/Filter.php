<?php
/**
 * @author selcukmart
 * 31.01.2021
 * 10:18
 */

namespace FormGenerator\Tools;


use FormGenerator\FormGenerator;

class Filter
{
    private $formGenerator;

    public function __construct(FormGenerator $formGenerator)
    {
        $this->formGenerator = $formGenerator;
    }

    /**
     * input-filter
     * input-group-filter
     * input-excluding-filter
     * input-excluding-group-filter
     *
     * @author selcukmart
     * 31.01.2021
     * 15:11
     */
    public function hasFilter()
    {
        if (isset($_GET['input-filter']) || isset($_GET['input-group-filter']) || isset($_GET['input-excluding-filter']) || isset($_GET['input-excluding-group-filter'])) {

            $this->formGenerator->setHasFilter(true);

            if (isset($_GET['input-filter'])) {
                $this->formGenerator->setInputFilter(explode(',', $_GET['input-filter']));
            }

            if (isset($_GET['input-excluding-filter'])) {
                $this->formGenerator->setInputExcludingFilter(explode(',', $_GET['input-excluding-filter']));
            }

            if (isset($_GET['input-group-filter'])) {
                $this->formGenerator->setInputGroupFilter(explode(',', $_GET['input-group-filter']));
            }

            if (isset($_GET['input-excluding-group-filter'])) {
                $this->formGenerator->setInputGroupExcludingFilter(explode(',', $_GET['input-excluding-group-filter']));
            }
        }
    }

    public function willFiltered($item, $group)
    {
        /**
         * Derece yani yetki üzerinden filtreleme
         */
        if (isset($item['derece']) && (!isset($_SESSION['giris']['derece']) || $item['derece'] > $_SESSION['giris']['derece'])) {
            return true;
        }
        /**
         * bu input un eklemede mi yoksa düzenlemede mi gösterileceğini belirler
         * get ile gelen filtrlemeden bağımsızdır.
         */
        if (isset($item['scope'])) {
            if ($item['scope'] != $this->formGenerator->getScope()) {
                return true;
            }
        }

        /**
         * get ile gelen bir filtreleme talebi yoksa
         */
        if (!$this->formGenerator->isHasFilter()) {
            return false;
        }

        if (!is_numeric($group) && is_string($group)) {
            if (sizeof($this->formGenerator->getInputGroupFilter()) > 0) {
                if (in_array($group, $this->formGenerator->getInputGroupFilter())) {
                    return false;
                } else {
                    return true;
                }
            }

            if (in_array($group, $this->formGenerator->getInputGroupExcludingFilter())) {
                return true;
            }
        }

        if (!empty($item['input-id'])) {
            if (sizeof($this->formGenerator->getInputFilter()) > 0) {
                if (in_array($item['input-id'], $this->formGenerator->getInputFilter())) {
                    return false;
                } else {
                    return true;
                }
            }

            if (in_array($item['input-id'], $this->formGenerator->getInputExcludingFilter())) {
                return true;
            }
        }


        if (sizeof($this->formGenerator->getInputGroupFilter()) > 0) {
            if (isset($item['group']) && in_array($item['group'], $this->formGenerator->getInputGroupFilter())) {
                return false;
            } else {
                return true;
            }
        }


        if ((isset($item['group']) && in_array($item['group'], $this->formGenerator->getInputGroupExcludingFilter()))) {
            return true;
        }

        return false;

    }

    public function __destruct()
    {

    }
}
