<?php

/*
 * This file is part of the Pinterest PHP library.
 *
 * (c) Hans Ott <hansott@hotmail.be>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.md.
 *
 * Source: https://github.com/hansott/pinterest-php
 */

namespace Pinterest\Tests;

use Pinterest\Api;
use Pinterest\Authentication;
use Pinterest\Http\BuzzClient;
use Pinterest\Image;
use Pinterest\Objects\PagedList;
use RuntimeException;

class ApiTest extends TestCase
{
    /**
     * @var Api
     */
    protected $api;

    protected $boardId;

    public function setUp()
    {
        $cacheDir = sprintf('%s/responses', __DIR__);
        if (!is_dir($cacheDir)) {
            throw new RuntimeException('The cache directory does not exist or is not a directory');
        }
        $client = new BuzzClient();
        $mocked = new MockClient($client, $cacheDir);
        $auth = Authentication::withAccessToken($mocked, null, null, getenv('ACCESS_TOKEN'));
        $this->api = new Api($auth);
        $this->boardId = getenv('BOARD_ID');
    }

    public function test_it_gets_users()
    {
        $this->assertUser($this->api->getUser('otthans'));
        $this->assertUser($this->api->getUser('314196648911734959'));

        $this->setExpectedException('InvalidArgumentException');
        $this->api->getUser('');
    }

    public function test_it_gets_a_board()
    {
        $this->assertBoard($this->api->getBoard('314196580192658592'));
    }

    public function test_it_gets_the_users_boards()
    {
        $this->assertMultipleBoards($this->api->getUserBoards());
    }

    public function test_it_gets_the_users_pins()
    {
        $this->assertMultiplePins($this->api->getUserPins());
    }

    public function test_it_gets_the_current_user()
    {
        $this->assertUser($this->api->getCurrentUser());
    }

    public function test_it_get_the_users_followers()
    {
        $this->assertMultipleUsers($this->api->getUserFollowers());
    }

    public function test_it_gets_the_boards_that_the_user_follows()
    {
        $this->assertMultipleBoards($this->api->getUserFollowingBoards());
    }

    public function test_it_gets_the_users_that_the_user_follows()
    {
        $this->assertMultipleUsers($this->api->getUserFollowing());
    }

    public function test_it_gets_the_users_interests()
    {
        $this->assertMultipleBoards($this->api->getUserInterests());
    }

    public function test_it_follows_a_user()
    {
        $username = 'engagor';
        $response = $this->api->followUser($username);
        $this->assertInstanceOf('Pinterest\Http\Response', $response);
        $this->assertTrue($response->ok());

        $this->setExpectedException('InvalidArgumentException');
        $username = '';
        $this->api->followUser($username);
    }

    /**
     * @dataProvider imageProvider
     */
    public function test_it_creates_a_pin(Image $image, $note)
    {
        $response = $this->api->createPin(
            $this->boardId,
            $note,
            $image
        );

        $this->assertInstanceOf('Pinterest\Http\Response', $response);
        $this->assertTrue($response->ok());
        $headers = $response->getHeaders();
        $this->assertEquals($response->getRateLimit(), $headers['X-Ratelimit-Limit']);
        $this->assertEquals($response->getHeader('X-Ratelimit-Limit'), $headers['X-Ratelimit-Limit']);
        $this->assertEquals($response->getRemainingRequests(), $headers['X-Ratelimit-Remaining']);
        $this->assertEquals($response->getHeader('X-Ratelimit-Remaining'), $headers['X-Ratelimit-Remaining']);

        $this->api->deletePin($response->result()->id);
    }

    public function imageProvider()
    {
        $imageFixture = __DIR__.'/fixtures/cat.jpg';

        return array(
            array(Image::url('http://lorempixel.com/g/400/200/cats/'), 'Test pin url'),
            array(Image::file($imageFixture), 'Test pin file'),
            array(Image::base64(base64_encode(file_get_contents($imageFixture))), 'Test pin base64'),
        );
    }

    public function test_it_deletes_a_pin()
    {
        $data = $this->imageProvider();
        $createResponse = $this->api->createPin(
            $this->boardId,
            $data[0][1],
            $data[0][0]
        );
        $this->assertInstanceOf('Pinterest\Http\Response', $createResponse);
        $this->assertTrue($createResponse->ok());

        $pinId = $createResponse->result()->id;
        $response = $this->api->deletePin($pinId);
        $this->assertInstanceOf('Pinterest\Http\Response', $response);
        $this->assertTrue($response->ok());
    }

    public function test_it_creates_and_updates_and_deletes_a_board()
    {
        $response = $this->api->createBoard('Unit test', 'A simple description');
        $this->assertInstanceOf('Pinterest\Http\Response', $response);
        $this->assertInstanceOf('Pinterest\Objects\Board', $response->result());
        $this->assertTrue($response->ok());

        $board = $response->result();
        $boardId = $board->id;
        $board->name = 'Unit test update';

        $response = $this->api->updateBoard($board);
        $this->assertInstanceOf('Pinterest\Http\Response', $response);
        $this->assertInstanceOf('Pinterest\Objects\Board', $response->result());
        $this->assertTrue($response->ok());

        $response = $this->api->deleteBoard($boardId);
        $this->assertInstanceOf('Pinterest\Http\Response', $response);
        $this->assertTrue($response->ok());
    }

    public function test_it_cannot_get_more_items_for_an_empty_list()
    {
        $pagedList = new PagedList(array(), null);
        $this->setExpectedException('InvalidArgumentException');
        $this->api->getNextItems($pagedList);
    }

    public function test_it_returns_the_pins_of_a_board()
    {
        $this->assertMultiplePins($this->api->getBoardPins($this->boardId));
    }
}
