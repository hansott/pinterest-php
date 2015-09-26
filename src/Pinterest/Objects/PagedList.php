<?php

namespace Pinterest\Objects;

/**
 * This class represents a paged list.
 *
 * @author Hans Ott <hansott@hotmail.be>
 */
class PagedList extends BaseObject
{
    private $items;
    private $nextUrl;

    public function __construct(array $items = [], $nextUrl = null)
    {
        $this->items = $items;
        $this->nextUrl = $nextUrl;
    }

    public function items()
    {
        return $this->items;
    }

    public function hasNext()
    {
        return isset($this->nextUrl);
    }

    public function getNextUrl()
    {
        return $this->nextUrl;
    }
}
