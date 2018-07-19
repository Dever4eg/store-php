<?php
/**
 * Created by PhpStorm.
 * User: dever
 * Date: 7/14/18
 * Time: 23:19
 */

namespace Src\Database;



abstract class ActiveRecordModel
{
    public static $table = null;

    public static function getTableName()
    {
        $class = explode('\\', get_called_class());
        $class = array_pop($class);
        $table = !empty(static::$table) ? static::$table : strtolower($class). 's';
        return $table;
    }

    protected static function getDB()
    {
        $db = DB::instance();
        $class = get_called_class();
        $db->setObjectClass($class);

        return $db;
    }

    protected static function addSqlParams($params = [])
    {
        $sql = '';
        foreach ($params as $param => $value) {
            switch (strtolower($param)) {
                case "limit":
                    $sql .= ' LIMIT '. $value;
                    break;
                case "offset":
                    $sql .= ' OFFSET '. $value;
                    break;
            }
        }
        return $sql;
    }

    public static function query()
    {
        return new QueryBuilder( get_called_class());
    }


    public static function all($params = [])
    {
        return self::query()->get();
    }

    public static function getById($id)
    {
        $result = self::query()->where('id', '=', $id)->get();

        return empty($result) ? null : $result[0];
    }


    public function save()
    {
        return empty($this->id) ? $this->insert() : $this->update();
    }

    public function insert()
    {
        $db = self::getDB();

        $params = get_object_vars($this);

        $sql = 'INSERT INTO '. self::getTableName() .
            '('. implode(',', array_keys($params)) .')'.
            'VALUES (:'.implode(', :', array_keys($params)).')';

        $db->execute($sql, $params);
        $this->id = $db->getLastInsertId();
    }

    public function update()
    {
        $db = self::getDB();

        $params = get_object_vars($this);
        $params_str = [];                   // ['key=:key', 'key=:key']

        foreach (array_diff(array_keys($params), ['id']) as $key) {
            $params_str[] = $key .'=:'. $key;
        }

        $sql = 'UPDATE '.self::getTableName() .
            ' SET ' . implode(', ', $params_str) .
            ' WHERE id=:id';

        $db->execute($sql, $params);
        $this->id = $db->getLastInsertId();
    }

    /**
     * @param $relation
     * @return RelationShip
     * @throws QueryBuilderException
     */
    public static function getRelation($relation)
    {
        $relations = static::relations();

        if(!array_key_exists($relation, $relations)) {
            throw new QueryBuilderException('Relation \''. $relation . '\' not fount in '. self::class);
        }
        return $relations[$relation];
    }

    public function associate($relation, $model)
    {
        $r = self::getRelation($relation);

        $fk = $r->getForeignKey();
        $rk = $r->getReferenceKey();
        $type = $r->getType();

        switch ($type) {
            case RelationShip::BELONGS_TO:
                $this->$fk = $model->$rk;
                return $this;
            case RelationShip::HAS_MANY:
                if(is_array($model)) {
                    foreach ($model as $item) {
                        $this->associate($relation, $item);
                    }
                } else {
                    $model->$fk = $this->$rk;
                }
                return $model;
        }


        return $this;
    }

    public static function insertArray(array $models)
    {
        $db = self::getDB();

        $params = array_keys(get_object_vars($models[0]));

        $pdo_params = [];
        $sql_items = [];

        foreach ($models as $k => $item) {
            $keys = [];
            foreach ($params as $param) {
               $keys[] = ':'.$param . $k;
               $pdo_params[$param . $k]= $item->$param;
            }
            $sql_items[] .= '(' . implode(', ', $keys ) . ')';
        }

        $sql = 'INSERT INTO '. self::getTableName() .
            ' ('. implode(', ', $params) .')'.
            ' VALUES ' . implode(', ', $sql_items);


        $db->execute($sql, $pdo_params);
    }

    public static function hasOne($model_class, $foreign_key, $reference_key)
    {
        return new RelationShip(RelationShip::HAS_ONE, $model_class, $foreign_key, $reference_key);
    }

    public static function hasMany($model_class, $foreign_key, $reference_key)
    {
        return new RelationShip(RelationShip::HAS_MANY, $model_class, $foreign_key, $reference_key);
    }

    public static function belongsTo($model_class, $foreign_key, $reference_key)
    {
        return new RelationShip(RelationShip::BELONGS_TO, $model_class, $foreign_key, $reference_key);
    }

}