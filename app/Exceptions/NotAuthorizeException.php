<?php

namespace App\Exceptions;

use Exception;
use App\Helpers\ApiExceptionHandler;

class NotAuthorizeException extends Exception
{
    use ApiExceptionHandler;
    public function render(){
    	return $this->renderException('NotAuthorizeException','You can not Authrize ', 403);
    }
}
