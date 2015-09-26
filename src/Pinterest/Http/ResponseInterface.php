<?php

namespace Pinterest\Http;

interface ResponseInterface
{
    public function ok();

    public function setResult($result);

    public function result();
}
