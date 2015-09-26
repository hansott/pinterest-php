<?php

namespace Pinterest\Objects;

/**
 * This class represents a Pinterest user.
 */
class User extends BaseObject
{
    /**
     * The user's id.
     *
     * @var string
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
     * @var DateTime
     */
    public $created_at;

    /**
     * The stats/counts of the User (follower Pins, likes, boards).
     *
     * @var Stats
     */
    public $counts;

    /**
     * The user's id.
     *
     * @var string
     */
    public $image;
}
