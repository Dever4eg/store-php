<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 13.07.18
 * Time: 12:19
 */

namespace App;


use Src\App;
use Src\App\FrontController as BaseFrontController;

class FrontController extends BaseFrontController
{
    public function run()
     {
         require_once __DIR__ . "/routes/web.php";

         App::run();
     }

}