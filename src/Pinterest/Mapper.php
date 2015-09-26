<?php

namespace Pinterest;

use ArrayObject;
use Pinterest\Http\Response;
use Pinterest\Http\ResponseError;
use Pinterest\Objects\BaseObject;
use JsonMapper;

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
    private function convertArrayObject(ArrayObject $object)
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
        $data = $response->body->response;
        $items = $this->mapper->mapArray(
            $data,
            new ArrayObject(),
            get_class($this->class)
        );

        return $this->convertArrayObject($items);
    }
}
