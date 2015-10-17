<?php

namespace Pinterest\Objects;

/**
 * This class represents a user.
 *
 * @author Hans Ott <hansott@hotmail.be>
 */
final class User implements BaseObject
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
            'username',
            'first_name',
            'last_name',
            'bio',
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
     * The user's id.
     *
     * @var string
     * @required
     */
    public $id;

    /**
     * The user's Pinterest username.
     *
     * @var string
     */
    public $username;

    /**
     * The user's first name.
     *
     * @var string
     */
    public $first_name;

    /**
     * The user's last name.
     *
     * @var string
     */
    public $last_name;

    /**
     * The user's bio.
     *
     * @var string
     */
    public $bio;

    /**
     * Timestamp of creation date.
     *
     * @var \DateTime
     */
    public $created_at;

    /**
     * The stats/counts of the User (follower Pins, likes, boards).
     *
     * @var Stats
     */
    public $counts;

    /**
     * The images that represents the user.
     *
     * This is determined by the request.
     *
     * @var array
     */
    public $image;
}
