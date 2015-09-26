<?php

namespace Pinterest\Http;

interface RequestInterface
{
    public function getMethod();

    public function getEndpoint();

    public function getParams();
}
