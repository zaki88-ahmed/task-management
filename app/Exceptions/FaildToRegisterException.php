<?php

namespace App\Exceptions;

use App\Helpers\ApiExceptionHandler;
use Exception;

class FaildToRegisterException extends Exception
{
    use ApiExceptionHandler;
    public function render()
    {
        return $this->renderException('Faild To Register ',"You Cant Register try it again",'404');
    }
}
