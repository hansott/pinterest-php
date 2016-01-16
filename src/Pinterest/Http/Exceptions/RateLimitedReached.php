<?php

/*
 * This file is part of the Pinterest PHP library.
 *
 * (c) Hans Ott <hansott@hotmail.be>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.md.
 *
 * Source: https://github.com/hansott/pinterest-php
 */

namespace Pinterest\Http\Exceptions;

use Pinterest\Http\Response;

/**
 * This exception will be thrown when the rate limit is reached.
 *
 * @author Toon Daelman <spinnewebber_toon@hotmail.com>
 */
final class RateLimitedReached extends \Exception
{
    private $response;

    public function __construct(Response $response)
    {
        $this->response = $response;

        parent::__construct('Rate limit reached.');
    }

    public function getResponse()
    {
        return $this->response;
    }
}
