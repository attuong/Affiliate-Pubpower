<?php

namespace core;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of model
 *
 * @author TungDT
 */
class Model {

    public static $instance = [];

    /**
     *
     * @var \MySQL $db
     */
    public $db;
    public $table = null;
    public $defalt_table = null;
    private $_configs = [
        'dbuser' => DB_USER,
        'dbpassword' => DB_PASSWORD,
        'dbname' => DB_NAME,
        'dbhost' => DB_HOST,
        'dbport' => DB_PORT,
        'encoding' => DB_ENCODING
    ];

    public function __construct($config = [], $prefix = false) {
        if ($config) {
            $this->_configs = $config;
        }
        $this->db = \MySQL::getInstance($this->_configs, $prefix);
    }

    public static function getInstance($config = []) {
        $current_class_name = get_called_class();
        if (!isset(static::$instance[$current_class_name]) || null === static::$instance[$current_class_name]) {
            static::$instance[$current_class_name] = new static($config);
        }
        return static::$instance[$current_class_name];
    }

    public function table() {
        if ($this->table) {
            $table = $this->table;
            $this->table = null;
        } else {
            $table = $this->defalt_table;
        }
        return $table;
    }

    public function setDefaultTable($table) {
        $this->defalt_table = $table;
    }

    public function setTable($table) {
        $this->table = $table;
        return $this;
    }

    public function setQuery($query) {
        $this->db->setQuery($query);
        return $this;
    }

    public function setField($field) {
        $this->db->setField($field);
        return $this;
    }

    public function setOrder($order) {
        $this->db->setOrder($order);
        return $this;
    }

    public function setLimit($limit) {
        $this->db->setLimit($limit);
        return $this;
    }

    public function getTotal() {
        return $this->db->getTotal();
    }

    public function insert($data = []) {
        return $this->db->insert($this->table(), $data);
    }

    public function update($query = [], $data = []) {
        return $this->db->update($this->table(), $query, $data);
    }

    public function remove($query = []) {
        return $this->db->remove($this->table(), $query);
    }

    public function getById($id, $field = []) {
        return $this->db->getById($this->table(), $id, $field);
    }

    public function find($query = [], $field = [], $order = [], $limit = false) {
        return $this->db->find($this->table(), $query, $field, $order, $limit);
    }

    public function findOne($query = [], $field = [], $order = []) {
        return $this->db->findOne($this->table(), $query, $field, $order);
    }

    public function findCol($column_number = 0, $query = array(), $field = array(), $order = array(), $limit = FALSE) {
        return $this->db->findCol($this->table(), $column_number, $query, $field, $order, $limit);
    }

    public function count($query = [], $field = ['id']) {
        return $this->db->count($this->table(), $query, $field);
    }

    public function sum($query = [], $field = ['id']) {
        return $this->db->sum($this->table(), $query, $field);
    }

    public function get_results($query = null, $output = OBJECT) {
        return $this->db->get_results($query, $output);
    }

    public function get_row($query = null, $output = OBJECT, $y = 0) {
        return $this->db->get_row($query, $output, $y);
    }

    public function get_var($query = null, $x = 0, $y = 0) {
        return $this->db->get_var($query, $x, $y);
    }

    public function debug() {
        return $this->db->debug();
    }

    public function errorReturn($error) {
        return array('error' => $error);
    }

    public function groupBy($field) {
        $this->db->setGroupBy($field);
        return $this;
    }

    public function join($join, $on) {
        $this->db->setJoin("JOIN", $join, $on);
        return $this;
    }

    public function innerJoin($join, $on) {
        $this->db->setJoin("INNER JOIN", $join, $on);
        return $this;
    }

    public function leftJoin($join, $on) {
        $this->db->setJoin("LEFT JOIN", $join, $on);
        return $this;
    }

    public function rightJoin($join, $on) {
        $this->db->setJoin("RIGHT JOIN", $join, $on);
        return $this;
    }

}
