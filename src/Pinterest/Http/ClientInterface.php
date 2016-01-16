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

namespace Pinterest\Http;

/**
 * All http clients need to implement this.
 *
 * Implement this interface to create your own http client.
 * When you need extra logging for example.
 *
 * @author Hans Ott <hansott@hotmail.be>
 */
interface ClientInterface
{
    /**
     * Executes a http request.
     *
     * @param Request $request The http request.
     *
     * @return Response The http response.
     */
    public function execute(Request $request);
}
