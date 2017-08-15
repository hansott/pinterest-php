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

/**
 * This class represents a board.
 *
 * @author Hans Ott <hansott@hotmail.be>
 */
final class Board implements BaseObject
{
    /**
     * The required fields.
     *
     * @return array The required fields.
     */
    public static function fields()
    {
        return array(
            'id',
            'name',
            'url',
            'description',
            'creator',
            'created_at',
            'counts',
            'image',
        );
    }

    /**
     * The boards's id.
     *
     * @var string
     * @required
     */
    public $id;

    /**
     * The name of the board.
     *
     * @var string
     */
    public $name;

    /**
     * The url to the object on pinterest.
     *
     * @var string
     */
    public $url;

    /**
     * The description of the board by the creator.
     *
     * @var string
     */
    public $description;

    /**
     * The user who created the board.
     *
     * @var User
     */
    public $creator;

    /**
     * ISO 8601 Timestamp of creation date.
     *
     * @var \DateTime
     */
    public $created_at;

    /**
     * The stats/counts of the Board (pins, collaborators and followers).
     *
     * @var Stats
     */
    public $counts;

    /**
     * Information about the media type, including whether it's an "image" or "video"..
     *
     * @var array
     */
    public $image;
}
