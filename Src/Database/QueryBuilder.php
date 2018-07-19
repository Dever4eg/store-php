<?php
/**
 * Created by PhpStorm.
 * User: dever
 * Date: 18.07.18
 * Time: 0:39
 */

namespace Src\Database;


use Psr\Http\Message\ServerRequestInterface;
use Src\App;

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
        $this->with[$relation] = ($this->class)::getRelation($relation);

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

    private function fetchRelated($object)
    {
        /**
         * @var RelationShip $relation
         */
        foreach ($this->with as $alias => $relation) {

            $class  = $relation->getModelClass();
            $fk     = $relation->getForeignKey();
            $rk     = $relation->getReferenceKey();
            $type   = $relation->getType();

            switch($type) {
                case RelationShip::BELONGS_TO:  $this->fetchBelongsTo($object, $alias, $fk, $rk, $class);   break;
                case RelationShip::HAS_MANY:    $this->fetchHasMany($object, $alias, $fk, $rk, $class);     break;
            }
        }
    }


    private function fetchBelongsTo($objects, $alias, $fk, $rk, $class)
    {
        // Получаем ключи которые нужно быбрать для всех обьектов
        $rks = [];
        foreach ($objects as $row) {
            $rks[] = $row->$fk;
        }
        $rks = array_unique($rks);

        $query = $class::query()
            ->where($rk, 'IN', '('.implode(', ', $rks).')')
            ->get();

        $related = [];

        foreach ($query as $row) {
            $related[$row->id] = $row;
        }

        // Записываем значения в исходный обьект
        foreach ($objects as $row) {
            $row->$alias = $related[$row->$fk];
        }
    }

    private function fetchHasMany($objects, $alias, $fk, $rk, $class)
    {
        $rks = [];
        foreach ($objects as $object) {
            $rks[] =$object->$rk;
        }
        $rks = array_unique($rks);

        $query = $class::query()
            ->where($fk, 'IN', '('.implode(',', $rks ).')')
            ->get();

        $related = [];

        foreach ($query as $row) {
            $related[$row->$fk][] = $row;
        }

        // Записываем значения в исходный обьект
        foreach ($objects as $row) {
            $row->$alias = $related[$row->$rk];
        }
    }

    public function get()
    {
        $result = $this->db->query($this->getSql(), $this->pdo_params);

        if(empty($result)) {
           return null;
        }

        if(!empty($this->with)) {
           $this->fetchRelated($result);
        }

        return $result;
    }

    public function one()
    {
        $this->limit = 0;
        $result = $this->get();

        return empty($result) ? null : $result[0];
    }

    public function count()
    {
        $sql = "SELECT count(*) as count ".
            "FROM ". $this->table .
            $this->SqlGenWhere();

        $count =  $this->db->query($sql, $this->pdo_params);

        return $count[0]->count;
    }

    public function paginate(int $perPage, ServerRequestInterface $request)
    {
        $page = $request->getQueryParams()['page'] ?? 1;

        $this->limit($perPage);
        $this->offset($perPage*($page-1));

        $results = $this->get();
        $count = (int)$this->count();

        $last_page = (int)ceil($count/$perPage);

        $path = App::getRouter()->getMatch($request)->url;

        return [
            'total'         => $count,
            'per_page'      => $perPage,
            'current_page'  => $page,
            'last_page'     => $last_page,
            'path'          => $path,

            "first_page_url"    => $path.'?page=1',
            "last_page_url"     => $path.'?page='.$last_page,
            "next_page_url"     => $page < $last_page ? $path.'?page='.($page+1) : null,
            "prev_page_url"     => $page > 1 ? $path.'?page='.($page-1) : null,

             "results"       => $results,
        ];
    }
}