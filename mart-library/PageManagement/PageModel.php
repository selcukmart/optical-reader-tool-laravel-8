<?php
/**
 * @author selcukmart
 * 19.12.2021
 * 16:09
 */

namespace PageManagement;

use Request;

class PageModel
{
    const TABLE = 'page';
    const TABLE_AS = 'p';

    private
        $page,
        $table_object,
        $page_data;

    public function __construct($page)
    {
        $this->page = $page;
        $this->getPageFromDB();
    }


    public function __get($name)
    {
        return $this->page_data[$name];
    }

    public function __isset($name)
    {
        return isset($this->page_data[$name]);
    }


    public static function instance($page = null)
    {
        if (is_null($page)) {
            $page = Request::getRequestUri();
        }
        $page = ltrim($page, '/');
        return new self($page);
    }

    private function getPageFromDB()
    {
        $arr = [
            'path' => $this->page
        ];

        $this->table_object = $this->getTableObject();
        $this->page_data = (array)  $this->table_object->where($arr)->limit(1)->first();
        if (empty($this->page_data)) {
            $id = $this->table_object->insertGetId($arr);
            $this->page_data = (array) $this->table_object->where(['id' => $id])->first();
        }
    }

    private function getTableObject()
    {
        if (!is_null($this->table_object)) {
            return $this->table_object;
        }
        $this->table_object = \DB::table(self::TABLE);
        return $this->table_object;
    }

    /**
     * @return mixed
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @return mixed
     */
    public function getPageData()
    {
        return $this->page_data;
    }

    public function __destruct()
    {

    }
}
