<?php

namespace App\Exceptions;

use Exception;
use App\Helpers\ApiExceptionHandler;

class NotFoundException extends Exception
{
    use ApiExceptionHandler;
    public function render(){
    	return $this->renderException('ContentNotFoundException','The Content you have requested is not found', 404);
    }
}
