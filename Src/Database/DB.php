<?php
/**
 * Created by PhpStorm.
 * User: dever
 * Date: 7/14/18
 * Time: 23:19
 */

namespace Src\Database;


use Src\App;
use Src\Traits\Singleton;
use PDO;

class DB
{
    use Singleton;

    private $pdo;
    private $obj_class = \stdClass::class;

    public function getPDO()
    {
        return $this->pdo;
    }

    private function __construct()
    {
        $config = App::getConfig()->get('database');

        $dsn = $config['adapter'].':dbname='.$config['name'].';host='.$config['host'];

        $this->pdo = new PDO($dsn, $config['user'], $config['pass']);
        $this->pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    }

    public function setObjectClass($class)
    {
        $this->obj_class = $class;
        return $this;
    }

    public function query($sql, $params = [])
    {
        $query = $this->pdo->prepare($sql);
        $query->execute($params);
        $results = $query->fetchAll(PDO::FETCH_CLASS, $this->obj_class);

        $this->obj_class = \stdClass::class;
        return $results;
    }

    public function execute($sql, $params = [])
    {
        $query = $this->pdo->prepare($sql);
        return $query->execute($params);
    }

    public function getLastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
}