<?php

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
            'url',
            'id',
            'name',
            'description',
            'creator',
            'created_at',
            'counts',
            'image',
        );
    }

    /**
     * The url to the object on pinterest.
     *
     * @var string
     */
    public $url;

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
     * The stats/counts of the Pin (repins,likes, comments).
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
