<?php

namespace Pinterest\Http;

/**
 * All response classes must implement this.
 *
 * @author Hans Ott <hansott@hotmail.be>
 */
interface ResponseInterface
{
    public function ok();

    public function setResult($result);

    public function result();
}
