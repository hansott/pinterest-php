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

namespace Pinterest\Objects;

use InvalidArgumentException;
use Pinterest\Authentication;

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
        $this->guardThatTheseAreAllPinterestObjects($items);
        $this->assertValidUri($nextUrl);
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
     * @return bool Whether there are more items.
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

    /**
     * Checks if all items are pinterest objects.
     *
     * @param array $items
     *
     * @throws InvalidArgumentException
     */
    private function guardThatTheseAreAllPinterestObjects(array $items)
    {
        foreach ($items as $item) {
            if (!($item instanceof BaseObject)) {
                throw new InvalidArgumentException(sprintf(
                    'Expected "Pinterest\Objects\BaseObject" but instead got: "%s"',
                    is_object($item) ? get_class($item) : gettype($item)
                ));
            }
        }
    }

    /**
     * Checks if the next uri is valid.
     *
     * @throws InvalidArgumentException
     *
     * @param $nextUri
     */
    private function assertValidUri($nextUri)
    {
        if ($nextUri === null) {
            return;
        }

        if (strpos($nextUri, Authentication::BASE_URI) === false) {
            throw new InvalidArgumentException(
                'Not a pinterest api uri'
            );
        }
    }
}
