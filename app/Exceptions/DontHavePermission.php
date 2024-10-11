<?php

namespace App\Exceptions;

use Exception;
use App\Helpers\ApiExceptionHandler;

class DontHavePermission extends Exception
{
    use ApiExceptionHandler;
    public function render(){   
          	return $this->renderException('DontHavePermission','You Dont Have Permission', 404);   
          }
}
