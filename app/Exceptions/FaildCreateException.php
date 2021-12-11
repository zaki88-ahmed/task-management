<?php

namespace App\Exceptions;

use Exception;
use App\Helpers\ApiExceptionHandler;

class FaildCreateException extends Exception
{
    use ApiExceptionHandler;
    public function render(){
    	return $this->renderException('FaildCreateException','You can not create ', 400);
    }
}
