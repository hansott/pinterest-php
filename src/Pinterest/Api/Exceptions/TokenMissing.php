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
