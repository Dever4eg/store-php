<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 20.07.18
 * Time: 11:44
 */

namespace Src\Validation;


use Src\Database\DB;
use Progsmile\Validator\Validator as V;

class Validator
{

    public function initDB()
    {
        V::setPDO(DB::instance()->getPDO());
    }

    public function validate(array $params, array $rules, array $messages = [])
    {
        return V::make($params, $rules, $messages);
    }
}