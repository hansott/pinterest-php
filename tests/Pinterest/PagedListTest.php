<?php

namespace Pinterest\Tests;

use InvalidArgumentException;
use Pinterest\Authentication;
use Pinterest\Objects\PagedList;
use Pinterest\Objects\User;

class PagedListTest extends TestCase
{
    public function test_it_only_accepts_pinterest_objects()
    {
        new PagedList(array(new User()));

        $this->setExpectedException('InvalidArgumentException');
        new PagedList(array(1));

        $this->setExpectedException('InvalidArgumentException');
        new PagedList(array(1, new User()));
    }

    public function test_it_has_more_items()
    {
        new PagedList(array(new User()), Authentication::BASE_URI.'/me');

        $this->setExpectedException('InvalidArgumentException');
        new PagedList(array(new User()), 'next-uri');
    }

    public function test_it_returns_the_next_uri()
    {
        $uri = Authentication::BASE_URI.'/v1/me';
        $pagedList = new PagedList(array(new User()), $uri);
        $this->assertTrue($pagedList->hasNext());
        $this->assertEquals($uri, $pagedList->getNextUrl());

        $pagedList = new PagedList(array(new User()));
        $this->assertFalse($pagedList->hasNext());
    }

    public function test_it_returns_the_items()
    {
        $items = array(new User(), new User());
        $pagedList = new PagedList($items);
        $this->assertEquals($items, $pagedList->items());
    }
}
