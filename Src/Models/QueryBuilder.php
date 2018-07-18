<?php
/**
 * Created by PhpStorm.
 * User: dever
 * Date: 18.07.18
 * Time: 0:39
 */

namespace Src\Models;


use Src\DB;

class QueryBuilder
{
    private $db;

    private $table;


    private $where = [];
    private $order_by;
    private $limit;
    private $offset;

    private $pdo_params = [];


    public function __construct($model_class)
    {
        $this->table = $model_class::getTableName();
        $this->db = DB::instance()->setObjectClass($model_class);
    }

    public function where($field, $operator, $value)
    {
        $operator = strtoupper($operator);
        if( !($operator == "="  ||
            $operator == "<>"   ||
            $operator == "<"    ||
            $operator == ">"    ||
            $operator == ">="   ||
            $operator == "<="   ||
            $operator == "BETWEEN"  ||
            $operator == "LIKE"     ||
            $operator == "IN"
        )) {
            throw new QueryBuilderException('Operator '. $operator .' not found');
        }

        $this->where[] = ['field' => $field, 'operator' => $operator, 'value' => $value];

        return $this;
    }

    public function orderBy($field, $direction = 'asc')
    {
        $direction = strtoupper($direction);
        if( !($direction == "ASC" || $direction == "DESC" ) ) {
            throw new QueryBuilderException('Direction '. $direction .' not found');
        }
        $this->order_by = ["field" => $field, "direction" => $direction];

        return $this;
    }


    public function limit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    public function offset($offset)
    {
        $this->offset = $offset;

        return $this;
    }


    private function SqlGenWhere()
    {
        if (empty($this->where)) {
            return '';
        }

        $sql = [];

        foreach ($this->where as $item) {
            $sql[] = $item['field'] .' '. $item['operator'] .' :'. $item['field'];
            $this->pdo_params[$item['field']] = $item['value'];
        }

        $sql = " WHERE ". implode(" AND ", $sql);


        return $sql;
    }

    private function SqlGetOrderBy()
    {
        if(empty($this->order_by)) {
            return '';
        }

        $sql = ' ORDER BY ' . $this->order_by['field'] .' '. $this->order_by['direction'];

        return $sql;
    }

    private function SqlGenLimitOffset()
    {
        $sql = '';
        !empty($this->limit) && $sql .= ' LIMIT ' . $this->limit;
        !empty($this->offset) && $sql .= ' OFFSET ' . $this->offset;

        return $sql;
    }

    public function getSql()
    {
        $sql = "SELECT *".
                " FROM ". $this->table .
                $this->SqlGenWhere().
                $this->SqlGetOrderBy().
                $this->SqlGenLimitOffset();


        return $sql;
    }

    public function get()
    {
       $result = $this->db->query($this->getSql(), $this->pdo_params);
        return empty($result) ? false : $result;
    }
}