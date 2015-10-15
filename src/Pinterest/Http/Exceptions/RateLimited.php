<?php

namespace Pinterest\Http\Exceptions;

use Exception;
use Pinterest\Http\Response;

final class RateLimited extends Exception
{
    private $response;

    public function __construct(Response $response)
    {
        $this->response = $response;

        parent::__construct('Rate Limited');
    }

    public function getResponse()
    {
        return $this->response;
    }
}
