<?php

namespace BoredProgrammers\LaraBreadcrumb\Exception;

use Exception;
use Throwable;

class BreadcrumbException extends Exception
{

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
