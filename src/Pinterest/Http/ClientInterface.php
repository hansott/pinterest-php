<?php

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
