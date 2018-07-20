<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 12.07.18
 * Time: 13:47
 */

namespace Src;


use Src\Validation\Validator;

abstract class Controller
{

    public function validate(array $params, array $rules, array $messages = [], bool $db_needed = false)
    {
        $validator = new Validator();
        $db_needed && $validator->initDB();
        return $validator->validate($params, $rules, $messages = []);
    }
}