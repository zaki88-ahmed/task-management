<?php

namespace App\Exceptions;

use App\Helpers\ApiExceptionHandler;
use Exception;

class NotAllowedException extends Exception
{
    use ApiExceptionHandler;
    public function render()
    {
        return $this->renderException('Not Allowed  ',"You Cant Use this Route" ,'404');
    }
}
