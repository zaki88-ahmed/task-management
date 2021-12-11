<?php

namespace App\Exceptions;

use App\Helpers\ApiExceptionHandler;
use Exception;

class DontHavePermissionException extends Exception
{
    use ApiExceptionHandler;
    public function render()
    {
        return $this->renderException('Dont Have Permission Exception',"Why You are here you don't have this pesmission",'404');
    }

}
