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

namespace Pinterest;

use JsonMapper;
use ArrayObject;
use Pinterest\Http\Response;
use Pinterest\Objects\PagedList;
use Pinterest\Objects\BaseObject;

/**
 * This class maps an object to a response.
 *
 * @author Hans Ott <hansott@hotmail.be>
 */
final class Mapper
{
    protected $mapper;

    protected $class;

    public function __construct(BaseObject $class)
    {
        $this->class = $class;
        $this->mapper = new JsonMapper();
        $this->mapper->bStrictNullTypes = false;    // don't throw exception if any field is null
    }

    public function toSingle(Response $response)
    {
        $data = $response->body->data;

        return $this->mapper->map($data, $this->class);
    }

    /**
     * Converts an array object to array.
     *
     * @param \ArrayObject $object The array object to convert.
     *
     * @return array The converted array.
     */
    private function convertToArray(ArrayObject $object)
    {
        $arr = array();
        $iterator = $object->getIterator();
        while ($iterator->valid()) {
            $arr[] = $iterator->current();
            $iterator->next();
        }

        return $arr;
    }

    public function toList(Response $response)
    {
        $data = $response->body->data;
        $nextUrl = isset($response->body->page->next) ? $response->body->page->next : null;

        $items = $this->mapper->mapArray(
            $data,
            new ArrayObject(),
            get_class($this->class)
        );

        $items = $this->convertToArray($items);

        return new PagedList($items, $nextUrl);
    }
}
