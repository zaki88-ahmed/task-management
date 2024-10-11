<?php

namespace App\Exceptions;

use App\Helpers\ApiExceptionHandler;
use Exception;

class NotFoundManagerException extends Exception
{
    use ApiExceptionHandler;

    public function render()
    {
        return $this->renderException('NotFoundManagerException','There is No Manager yet !','404');

    }
}
