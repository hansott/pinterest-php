<?php

namespace Pinterest\Api\Exceptions;

use Exception;

/**
 * This exception is thrown when a request is being made without an access token.
 *
 * @author Hans Ott <hansott@hotmail.be>
 */
class TokenMissing extends Exception
{
}
