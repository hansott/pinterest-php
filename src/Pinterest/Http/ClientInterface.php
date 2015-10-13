<?php

namespace Pinterest\Http;

/**
 * All http clients need to implement this.
 *
 * @author Hans Ott <hansott@hotmail.be>
 */
interface ClientInterface
{
    /**
     * Execute an Http request
     *
     * @param Request $request The Http Request
     *
     * @return Response The Http Response
     */
    public function execute(Request $request);
}
