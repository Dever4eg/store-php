<?php
/**
 * Created by PhpStorm.
 * User: dever
 * Date: 18.07.18
 * Time: 0:39
 */

namespace Src\Models;


use Dever4eg\Logger;
use Src\App;
use Src\DB;

class QueryBuilder
{
    private $db;

    private $table;
    private $class;


    private $where = [];
    private $order_by;
    private $limit;
    private $offset;

    private $with = [];

    private $pdo_params = [];


    public function __construct($model_class)
    {
        $this->class = $model_class;
        $this->table = $model_class::getTableName();
        $this->db = DB::instance()->setObjectClass($model_class);
    }

    public function with($relation)
    {
        $relations = ($this->class)::relations();

        if(!array_key_exists($relation, $relations)) {
            throw new QueryBuilderException('Relation \''. $relation . '\' not fount in '. $this->class);
        }

        $this->with[$relation] = $relations[$relation];

        return $this;
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
            if($item['operator'] == 'IN') {
                $sql[] = $item['field'] .' IN '. $item['value'];
            } else {
                $sql[] = $item['field'] .' '. $item['operator'] .' :'. $item['field'];
                $this->pdo_params[$item['field']] = $item['value'];
            }
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

        if(empty($result)) {
           return null;
        }

        if(empty($this->with)) {
           return $result;
        }

        /**
         * @var RelationShip $relation
         */
        foreach ($this->with as $alias => $relation) {
            if($relation->getType() === RelationShip::HAS_ONE) {

                $class  = $relation->getModelClass();
                $fk     = $relation->getForeignKey();
                $rk     = $relation->getReferenceKey();

                $rks = [];
                foreach ($result as $row) {
                    $rks[] = $row->$fk;
                }
                $rks = array_unique($rks);

                $query = ( new QueryBuilder($class) )
                    ->where($rk, 'IN', '('.implode(', ', $rks).')')
                    ->get();

                $related = [];

                foreach ($query as $row) {
                    $related[$row->id] = $row;
                }

                foreach ($result as $row) {
                    $row->$alias = $related[$row->$fk];
                }
            }
        }


        return $result;
    }

    public function one()
    {
        $this->limit = 0;
        $result = $this->get();

        return empty($result) ? null : $result[0];
    }
}