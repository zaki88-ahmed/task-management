<?php

namespace App\Exceptions;

use Exception;
use App\Helpers\ApiExceptionHandler;

class NotFoundOrganization extends Exception
{
    use ApiExceptionHandler;
    public function render(){
    	return $this->renderException('NotFoundOrganization','You can not Found Organization ', 404);
    }
}
