<?php

namespace Pinterest\Http;

interface ClientInterface
{
    public function execute(Request $request, $token);
}
