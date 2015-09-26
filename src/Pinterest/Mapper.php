<?php

namespace Pinterest;

use ArrayObject;
use Pinterest\Objects\PagedList;
use Pinterest\Http\Response;
use Pinterest\Http\ResponseError;
use Pinterest\Objects\BaseObject;
use JsonMapper;

/**
 * This class maps an object to a response.
 *
 * @author Hans Ott <hansott@hotmail.be>
 */
class Mapper
{
    protected $mapper;

    protected $class;

    public function __construct(BaseObject $class)
    {
        $this->class = $class;
        $this->mapper = new JsonMapper();
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
        $arr = [];
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
