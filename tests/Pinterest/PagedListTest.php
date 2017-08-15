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

use stdClass;
use Pinterest\Objects\Pin;
use Pinterest\Objects\User;
use Pinterest\Objects\Board;
use InvalidArgumentException;
use Pinterest\Authentication;
use Pinterest\Objects\PagedList;

class PagedListTest extends TestCase
{
    public function validPinterestObjects()
    {
        return array(
            array(array(new User)),
            array(array(new Pin)),
            array(array(new Board)),
        );
    }

    /**
     * @dataProvider validPinterestObjects
     */
    public function test_it_accepts_pinterest_objects(array $objects)
    {
        new PagedList($objects);
    }

    public function invalidPinterestObjects()
    {
        return array(
            array(array(1, new User)),
            array(array('string')),
            array(array(new stdClass))
        );
    }

    /**
     * @dataProvider invalidPinterestObjects
     * @expectedException InvalidArgumentException
     */
    public function test_it_does_not_accept_non_pinterest_objects(array $objects)
    {
        new PagedList($objects);
    }

    public function test_it_has_more_items()
    {
        new PagedList(array(new User), Authentication::BASE_URI.'/me');

        $this->setExpectedException('InvalidArgumentException');
        new PagedList(array(new User), 'next-uri');
    }

    public function test_it_returns_the_next_uri()
    {
        $uri = Authentication::BASE_URI.'/v1/me';
        $pagedList = new PagedList(array(new User), $uri);
        $this->assertTrue($pagedList->hasNext());
        $this->assertSame($uri, $pagedList->getNextUrl());

        $pagedList = new PagedList(array(new User));
        $this->assertFalse($pagedList->hasNext());
    }

    public function test_it_returns_the_items()
    {
        $items = array(new User, new User);
        $pagedList = new PagedList($items);
        $this->assertSame($items, $pagedList->items());
    }
}
