<?php

use Pinterest\Http\Response;

class TestCase extends PHPUnit_Framework_TestCase
{
    public function assertUser(Response $response)
    {
        $this->assertInstanceOf('Pinterest\Http\Response', $response);
        $this->assertTrue($response->ok());
        $user = $response->result();
        $this->assertInstanceOf('Pinterest\Objects\User', $user);
    }
}
