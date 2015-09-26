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

    public function testGetUser()
    {
        $this->assertUser($this->api->getUser('otthans'));
        $this->assertUser($this->api->getUser('314196648911734959'));
    }

    public function testGetBoard()
    {
        $this->assertBoard($this->api->getBoard('314196580192594085'));
    }

    public function testGetUserBoards()
    {
        $this->assertMultipleBoards($this->api->getUserBoards());
    }

    public function testGetUserLikes()
    {
        $this->assertMultiplePins($this->api->getUserLikes());
    }

    public function testGetUserPins()
    {
        $this->assertMultiplePins($this->api->getUserPins());
    }

    public function testGetCurrentUser()
    {
        $this->assertUser($this->api->getCurrentUser());
    }

    public function testGetUserFollowers()
    {
        $this->assertMultipleUsers($this->api->getUserFollowers());
    }

    public function testGetUserFollowingBoards()
    {
        $this->assertMultipleBoards($this->api->getUserFollowingBoards());
    }

    public function testGetUserFollowing()
    {
        $this->assertMultipleUsers($this->api->getUserFollowing());
    }

    public function testGetUserInterests()
    {
        $this->assertMultiplePins($this->api->getUserInterests());
    }
}
