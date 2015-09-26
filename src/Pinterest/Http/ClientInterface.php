<?php

namespace Pinterest\Http;

/**
 * All http clients need to implement this.
 *
 * @author Hans Ott <hansott@hotmail.be>
 */
interface ClientInterface
{
    public function execute(Request $request, $token);
}
