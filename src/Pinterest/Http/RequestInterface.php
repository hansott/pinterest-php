<?php

namespace Pinterest\Http;

/**
 * All requests classes must implement this.
 *
 * @author Hans Ott <hansott@hotmail.be>
 */
interface RequestInterface
{
    public function getMethod();

    public function getEndpoint();

    public function getParams();
}
