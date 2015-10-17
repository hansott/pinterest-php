<?php

namespace Pinterest\Objects;

/**
 * This class represents a pin.
 *
 * @author Hans Ott <hansott@hotmail.be>
 */
final class Pin implements BaseObject
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
            'link',
            'creator',
            'board',
            'created_at',
            'note',
            'color',
            'counts',
            'media',
            'attribution',
            'image',
            'metadata',
        );
    }

    /**
     * The url to the object on pinterest.
     *
     * @var string
     */
    public $url;

    /**
     * The Pin's id.
     *
     * @var string
     * @required
     */
    public $id;

    /**
     * The URL of the web page where the Pin was created.
     *
     * @var string
     */
    public $link;

    /**
     * The user who created the pin.
     *
     * @var User
     */
    public $creator;

    /**
     * The board the Pin is in.
     *
     * @var Board
     */
    public $board;

    /**
     * ISO 8601 Timestamp of creation date.
     *
     * @var \DateTime
     */
    public $created_at;

    /**
     * The description of the Pin by the creator.
     *
     * @var string
     */
    public $note;

    /**
     * The dominant color of the Pin image.
     *
     * @var string
     */
    public $color;

    /**
     * The stats/counts of the Pin (repins,likes, comments).
     *
     * @var Stats
     */
    public $counts;

    /**
     * Information about the media type, including whether it's an "image" or "video".
     *
     * @var array
     */
    public $media;

    /**
     * Attribution information.
     *
     * @var array
     */
    public $attribution;

    /**
     * The images that represents the Pin. This is determined by the request.
     *
     * @var array
     */
    public $image;

    /**
     * Extra information including Pin type (recipe, article, etc.) and related data (ingredients, author, etc).
     *
     * @var array
     */
    public $metadata;
}
