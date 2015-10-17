<?php

namespace Pinterest\Objects;

/**
 * This class represents a paged list.
 *
 * @author Hans Ott <hansott@hotmail.be>
 */
final class PagedList
{
    /**
     * The paged list items.
     *
     * @var array
     */
    private $items;

    /**
     * The url for retrieving the next set of items.
     *
     * @var string
     */
    private $nextUrl;

    /**
     * Creates a new paged list.
     *
     * @param array  $items   The paged list items.
     * @param string $nextUrl The url for retrieving the next set of items.
     */
    public function __construct(array $items = array(), $nextUrl = null)
    {
        $this->items = $items;
        $this->nextUrl = $nextUrl;
    }

    /**
     * Returns the items.
     *
     * @return array The items.
     */
    public function items()
    {
        return $this->items;
    }

    /**
     * Returns whether the paged list has more items.
     *
     * @return boolean Whether there are more items.
     */
    public function hasNext()
    {
        return !empty($this->nextUrl);
    }

    /**
     * Returns the url to get the next set of items.
     *
     * @return string The url to get the next set of items.
     */
    public function getNextUrl()
    {
        return $this->nextUrl;
    }
}
