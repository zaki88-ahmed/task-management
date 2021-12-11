<?php

namespace App\Exceptions;

use App\Helpers\ApiExceptionHandler;
use Exception;

class NotFoundMemberShipException extends Exception
{
    use ApiExceptionHandler;

    public function render()
    {
        return $this->renderException('Not Found MemberShip ','This MemberShip Not Found Try Again !','404');

    }
}
