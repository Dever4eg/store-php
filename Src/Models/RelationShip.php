<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 18.07.18
 * Time: 13:14
 */

namespace Src\Models;


class RelationShip
{
    private $type;
    private $model_class;
    private $foreign_key;
    private $reference_key;

    public const HAS_ONE = 0;
    public const HAS_MANY = 1;
    public const BELONGS_TO = 2;
    public const BELONGS_TO_MANY = 4;

    public function __construct($type, $model_class, $foreign_key, $reference_key)
    {
        $this->type = $type;
        $this->model_class = $model_class;
        $this->foreign_key = $foreign_key;
        $this->reference_key = $reference_key;
    }

    public function getType()
    {
        return $this->type;
    }


    public function getModelClass()
    {
        return $this->model_class;
    }

    public function getForeignKey()
    {
        return $this->foreign_key;
    }

    public function getReferenceKey()
    {
        return $this->reference_key;
    }
}