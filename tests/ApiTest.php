<?php

use Pinterest\Api;
use Pinterest\Authentication;
use Pinterest\Http\GuzzleClient;
use Pinterest\Objects\User;

class ApiTest extends TestCase
{
    protected $api;

    public function setUp()
    {
        $client = new GuzzleClient();
        $auth = Authentication::withAccessToken($client, null, null, getenv('ACCESS_TOKEN'));
        $this->api = new Api($auth);
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

    public function testFollowUser()
    {
        $user = new User();
        $user->username = 'engagor';
        $response = $this->api->followUser($user);
        $this->assertInstanceOf('Pinterest\Http\Response', $response);
        $this->assertTrue($response->ok());

        $this->setExpectedException('InvalidArgumentException');
        $user = new User();
        $this->api->followUser($user);
    }
}
