<?php

use Pinterest\Api;
use Pinterest\Mapper;

class ApiTest extends TestCase
{
    protected $api;

    public function setUp()
    {
        $this->api = new Api(getenv('ACCESS_TOKEN'));
    }

    public function testGetCurrentUser()
    {
        $this->assertUser($this->api->getCurrentUser());
    }

    public function testGetUser()
    {
        $this->assertUser($this->api->getUser('otthans'));
        $this->assertUser($this->api->getUser(314196648911734959));
    }
}
