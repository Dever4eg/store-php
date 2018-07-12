<?php
/**
 * Created by PhpStorm.
 * User: dever
 * Date: 7/10/18
 * Time: 23:24
 */

namespace Src\Logging;

use Src\App;
use Src\App\AppSingleComponent;
use Dever4eg\Logger as DLogger;

class Logger extends DLogger implements AppSingleComponent, LoggerInterface
{
    public function __construct()
    {
        $file = App::getConfig()->get('log_path') ?? "/var/logs/base.log";
        parent::__construct($file);
    }
}