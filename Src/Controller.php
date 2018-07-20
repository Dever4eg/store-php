<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 12.07.18
 * Time: 13:47
 */

namespace Src;


use Progsmile\Validator\Helpers\ValidatorFacade;
use Src\Session\FlashMessage;
use Src\Validation\Validator;

abstract class Controller
{

    public function validate(array $params, array $rules, array $messages = [], bool $db_needed = false)
    {
        $validator = new Validator();
        $db_needed && $validator->initDB();
        return $validator->validate($params, $rules, $messages = []);
    }

    public function setValidationMessages(ValidatorFacade $validator)
    {
        App::getSession()->setFlashMessage(
            new FlashMessage('error', 'Validation error', $validator->first())
        );
    }
}