<?php

if (!defined('JSON_PRETTY_PRINT')) {
    define('JSON_PRETTY_PRINT', 128);
}

use Pinterest\Http\Response;

class TestCase extends \PHPUnit_Framework_TestCase
{
    protected function assertResponse(Response $response)
    {
        $this->assertInstanceOf('Pinterest\Http\Response', $response);
        $this->assertTrue($response->ok());
        $this->assertNotEmpty($response->result());
    }

    public function assertBoard(Response $response)
    {
        $this->assertResponse($response);
        $user = $response->result();
        $this->assertInstanceOf('Pinterest\Objects\Board', $user);
    }

    public function assertPin(Response $response)
    {
        $this->assertResponse($response);
        $user = $response->result();
        $this->assertInstanceOf('Pinterest\Objects\Pin', $user);
    }

    public function assertUser(Response $response)
    {
        $this->assertResponse($response);
        $user = $response->result();
        $this->assertInstanceOf('Pinterest\Objects\User', $user);
    }

    public function assertMultipleBoards(Response $response)
    {
        $this->assertPagedList($response, 'Pinterest\Objects\Board');
    }

    public function assertMultipleUsers(Response $response)
    {
        $this->assertPagedList($response, 'Pinterest\Objects\User');
    }

    public function assertMultiplePins(Response $response)
    {
        $this->assertPagedList($response, 'Pinterest\Objects\Pin');
    }

    private function assertPagedList(Response $response, $object)
    {
        $this->assertResponse($response);
        $pagedList = $response->result();
        $this->assertInstanceOf('Pinterest\Objects\PagedList', $pagedList);
        $items = $pagedList->items();
        $this->assertInternalType('array', $items);
        $this->assertContainsOnlyInstancesOf($object, $items);
    }
}
