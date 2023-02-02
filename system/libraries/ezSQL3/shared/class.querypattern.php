<?php

define('ERROR_NO_TABLE', '<span style="color:red">No table</span>');
define('ERROR_NO_FIELD', '<span style="color:red"><strong>{field}</strong> not have</span>');
define('ERROR_NO_CMD', '<span style="color:red"><strong>{cmd}</strong> not have</span>');
define('ERROR_UPDATE', 'Conditions and data update empty');

class QueryPattern {

    public $table = NULL;
    public $query = NULL;
    public $field = NULL;
    public $order = NULL;
    public $limit = NULL;
    public $data = NULL;
    public $group = NULL;
    public $join = NULL;
    public $lastTable = NULL;
    public $lastQuery = NULL;
    public $lastField = NULL;
    public $lastOrder = NULL;
    public $lastLimit = NULL;
    public $lastData = NULL;
    public $lastGroup = NULL;
    public $lastJoin = NULL;
    public $lastInnerJoin = NULL;
    public $lastLeftJoin = NULL;
    public $lastRightJoin = NULL;

    public function __construct() {
        $this->flushDataQuery();
    }

    public function flushDataQuery() {
        $this->setLogQuery();
        $this->table = NULL;
        $this->query = NULL;
        $this->field = NULL;
        $this->order = NULL;
        $this->limit = NULL;
        $this->data = NULL;
        $this->group = NULL;
        $this->join = NULL;
    }

    public function setLogQuery() {
        $this->lastTable = $this->table;
        $this->lastQuery = $this->query;
        $this->lastField = $this->field;
        $this->lastOrder = $this->order;
        $this->lastLimit = $this->limit;
        $this->lastData = $this->data;
        $this->lastGroup = $this->group;
        $this->lastJoin = $this->join;
    }

    /**
     *
     * @return string
     */
    public function insertQuery() {
        if (!$this->table) {
            return $this->error(ERROR_NO_TABLE);
        }
        $sql = "INSERT INTO `$this->table` SET " . $this->get_set($this->data);
        $this->flushDataQuery();
        return $sql;
    }

    /**
     *
     * @return string
     */
    public function updateQuery() {
        if (!$this->table) {
            return $this->error(ERROR_NO_TABLE);
        }
        if (!$this->query && !$this->data) {
            return $this->error(ERROR_UPDATE);
        }
        $sql = "UPDATE `$this->table` SET " . $this->get_set($this->data) . " " . $this->condition();
        $this->flushDataQuery();
        return $sql;
    }

    /**
     *
     * @return string
     */
    public function deleteQuery() {
        if (!$this->table) {
            return $this->error(ERROR_NO_TABLE);
        }
        $sql = "DELETE FROM `$this->table` " . $this->condition();
        $this->flushDataQuery();
        return $sql;
    }

    /**
     *
     * @return string
     */
    public function sumQuery() {
        if (!$this->table) {
            return $this->error(ERROR_NO_TABLE);
        }
        $_field = (!$this->field) ? '*' : implode(',', $this->field);
        //        if (isset($_GET['f'])) { // DEBUG
        //            foreach ($this->field as $key => $value) {
        //                $this->field[$key] = "SUM($value) as total_$value";
        //            }
        //            $_field = (!$this->field) ? '*' : implode(',', $this->field);
        //            $sql = "SELECT $_field AS total FROM `$this->table` " . $this->condition();
        //            $this->flushDataQuery();
        //            return $sql;
        //        }
        $sql = "SELECT SUM($_field) AS total FROM `$this->table` " . $this->condition();
        $this->flushDataQuery();
        return $sql;
    }

    /**
     *
     * @return string
     */
    public function countQuery() {
        if (!$this->table) {
            return $this->error(ERROR_NO_TABLE);
        }
        $_field = (!$this->field) ? '*' : implode(',', $this->field);
        $sql = "SELECT COUNT($_field) as total FROM `$this->table`";
        if ($this->join) {
            foreach ($this->join as $join_type => $data) {
                $_on_join = "";
                foreach ($data['on'] as $on_join_key => $on_join_value) {
                    $_on_join .= "$on_join_key = $on_join_value";
                }
                $sql .= " $join_type `{$data['table']}` ON $_on_join ";
            }
        }
        $sql .= $this->condition();
        if ($this->group) {
            $sql .= " GROUP BY $this->group ";
        }
        $this->flushDataQuery();
        return $sql;
    }

    /**
     *
     * @return string
     */
    public function selectQuery() {
        if (!$this->table) {
            return $this->error(ERROR_NO_TABLE);
        }
        $sql = "SELECT * FROM `$this->table`";
        if ($this->field) {
            $sql = "SELECT " . implode(",", $this->field) . " FROM `$this->table`";
        }
        if ($this->join) {
            foreach ($this->join as $join_type => $data) {
                $_on_join = "";
                foreach ($data['on'] as $on_join_key => $on_join_value) {
                    $_on_join .= "$on_join_key = $on_join_value";
                }
                $sql .= " $join_type `{$data['table']}` ON $_on_join ";
            }
        }
        $sql .= $this->condition();
        if ($this->group) {
            $sql .= " GROUP BY $this->group ";
        }
        if ($this->order) {
            $sql .= " ORDER BY ";
            if ($this->order === 'RAND()' || in_array('RAND()', $this->order)) {
                $sql .= "RAND()";
            } else {
                $_order = [];
                foreach ($this->order as $o_field => $o_value) {
                    $o_explode_value = explode("|", $o_value);
                    if (count($o_explode_value) > 1 && strtolower($o_explode_value[0]) === 'field') {
                        $_order[] = "FIELD($o_field,{$o_explode_value[1]})";
                    } else {
                        $_order[] = "`$o_field` $o_value";
                    }
                }
                $sql .= implode(",", $_order);
            }
        }
        if ($this->limit) {
            $sql .= " LIMIT $this->limit";
        }
        $this->flushDataQuery();
        return $sql;
    }

    /**
     *
     * @return \QueryPattern
     */
    public function condition() {
        if (!$this->query) {
            return FALSE;
        }
        $return = " WHERE ";
        $_ors = $_ands = [];
        foreach ($this->query as $field => $value) {
            if ($field === '$or') {
                // MULTI OR
                foreach ($value as $or) {
                    foreach ($or as $or_field => $or_value) {
                        if (is_array($or_value)) {
                            $_ors = array_merge($_ors, $this->condition_cmd($or_field, $or_value));
                        } else {
                            if (!is_int($or_value)) {
                                $or_value = "'" . $this->escape($or_value) . "'";
                            }
                            $_ors[] = "$or_field = $or_value";
                        }
                    }
                }
                $_ands[] = "(" . implode(" OR ", $_ors) . ")";
            } elseif ($field === '$and') {
                // MULTI AND
                foreach ($value as $or) {
                    foreach ($or as $or_field => $or_value) {
                        if (is_array($or_value)) {
                            $_ors = array_merge($_ors, $this->condition_cmd($or_field, $or_value));
                        } else {
                            if (!is_int($or_value)) {
                                $or_value = "'" . $this->escape($or_value) . "'";
                            }
                            $_ors[] = "$or_field = $or_value";
                        }
                    }
                }
                $_ands[] = "(" . implode(" AND ", $_ors) . ")";
            } elseif ($field === '$date') {
                foreach ($value as $or_field => $or_value) {
                    if (is_array($or_value)) {
                        $_ors = array_merge($_ors, $this->condition_cmd($or_field, $or_value));
                    } else {
                        if (!is_int($or_value)) {
                            $or_value = "'" . $this->escape($or_value) . "'";
                        }
                        $_ors[] = "$or_field = $or_value";
                    }
                }
                $_ands[] = "(" . implode(" AND ", $_ors) . ")";
            } else {
                // DEFAULT QUERY
                if (is_array($value)) {
                    $_ands = array_merge($_ands, $this->condition_cmd($field, $value));
                } else if ($value === NULL) {
                    $_ands[] = "$field is null";
                } else {
                    if (!is_int($value)) {
                        $value = "'" . $this->escape($value) . "'";
                    }
                    $_ands[] = "$field = $value";
                }
            }
        }
        $return .= implode(" AND ", $_ands);
        return $return;
    }

    /**
     *
     * @param string $field
     * @param array $query
     * @return \QueryPattern
     */
    public function condition_cmd($field, $query = []) {
        $return = [];
        foreach ($query as $cmd => $value) {
            if (!is_int($value) && !is_array($value)) {
                $value = "'" . $this->escape($value) . "'";
            }
            switch ($cmd) {
                case '$like':
                    $return[] = "$field LIKE $value";
                    break;

                case '$or':
                    $_ands = [];
                    foreach ($value as $or_key => $or_value) {
                        if (is_array($or_value)) {
                            $_ands = array_merge($_ands, $this->condition_cmd($field, $or_value));
                        } else {
                            if (!is_int($or_value)) {
                                $or_value = "'" . $this->escape($or_value) . "'";
                            }
                            $_ands[] = "$field = $or_value";
                        }
                    }
                    $return[] = "(" . implode(" OR ", $_ands) . ")";
                    break;

                case '$lt':
                case '<':
                    $return[] = "$field < $value";
                    break;

                case '$lte':
                case '<=':
                    $return[] = "$field <= $value";
                    break;

                case '$gt':
                case '>':
                    $return[] = "$field > $value";
                    break;

                case '$gte':
                case '>=':
                    $return[] = "$field >= $value";
                    break;

                case '$ne':
                case '!=':
                    $return[] = "$field != $value";
                    break;

                case '$in':
                    $in = [];
                    foreach ($value as $_value) {
                        if (!is_int($_value)) {
                            $_value = "'" . $this->escape($_value) . "'";
                        }
                        $in[] = $_value;
                    }
                    $return[] = "$field IN (" . implode(",", $in) . ")";
                    break;

                case '$nin':
                    $in = [];
                    foreach ($value as $_value) {
                        if (!is_int($_value)) {
                            $_value = "'" . $this->escape($_value) . "'";
                        }
                        $in[] = $_value;
                    }
                    $return[] = "$field NOT IN (" . implode(",", $in) . ")";
                    break;

                case '$between':
                    if (count($value) === 2) {
                        $return[] = "$field BETWEEN {$value[0]} AND {$value[1]}";
                    }
                    break;

                default:
                    return $this->error(str_replace(['{cmd}'], [$cmd], ERROR_NO_CMD));
                    break;
            }
        }
        return $return;
    }

    /**
     *
     * @param string $table
     * @return \MySQL
     */
    public function setTable($table = FALSE) {
        $this->table = $table;
        return $this;
    }

    public function getTable() {
        return $this->table;
    }

    /**
     *
     * @param array $query
     * @return \MySQL
     */
    public function setQuery($query = []) {
        $this->query = $query;
        return $this;
    }

    public function getQuery() {
        return $this->query;
    }

    /**
     *
     * @param array $field
     * @return \MySQL
     */
    public function setField($field = []) {
        $this->field = $field;
        return $this;
    }

    public function getField() {
        return $this->field;
    }

    /**
     *
     * @param array $order
     * @return \MySQL
     */
    public function setOrder($order = []) {
        $this->order = $order;
        return $this;
    }

    public function getOrder() {
        return $this->order;
    }

    /**
     *
     * @param string|integer|boolean $limit
     * @return \MySQL
     */
    public function setLimit($limit = FALSE) {
        $this->limit = $limit;
        return $this;
    }

    public function getLimit() {
        return $this->limit;
    }

    /**
     *
     * @param array $data
     * @return \MySQL
     */
    public function setData($data = []) {
        $this->data = $data;
        return $this;
    }

    public function getData() {
        return $this->data;
    }

    /**
     *
     * @param string|integer|boolean $group
     * @return \MySQL
     */
    public function setGroupBy($group = FALSE) {
        $this->group = $group;
        return $this;
    }

    public function getGroupBy() {
        return $this->group;
    }

    /**
     *
     * @param string $join_type
     * @param string $table
     * @param array $on
     * @return \MySQL
     */
    public function setJoin($join_type, $table = FALSE, $on = FALSE) {
        $this->join[$join_type]['table'] = $table;
        $this->join[$join_type]['on'] = $on;
        return $this;
    }

    public function getJoin() {
        return $this->join;
    }

    /**
     *
     * @param string $error
     * @return array
     */
    public function error($error) {
        return ['error' => $error];
    }

}
