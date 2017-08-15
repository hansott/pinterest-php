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
 * This class holds all general statistics.
 *
 * @author Hans Ott <hansott@hotmail.be>
 */
final class Stats
{
    /**
     * The amount of saves.
     *
     * @var int
     */
    public $saves;

    /**
     * The amount of comments.
     *
     * @var int
     */
    public $comments;

    /**
     * The amount of following.
     *
     * @var int
     */
    public $following;

    /**
     * The amount of followers.
     *
     * @var int
     */
    public $followers;

    /**
     * The amount of pins.
     *
     * @var int
     */
    public $pins;

    /**
     * The amount of collaborators.
     *
     * @var int
     */
    public $collaborators;
}
