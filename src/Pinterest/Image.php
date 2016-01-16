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

namespace Pinterest;

use InvalidArgumentException;

/**
 * This class represents an image.
 *
 * @author Toon Daelman <spinnewebber_toon@hotmail.com>
 */
final class Image
{
    const TYPE_URL = 'url';
    const TYPE_BASE64 = 'base64';
    const TYPE_FILE = 'file';

    private $type;
    private $data;

    private function __construct($type, $data)
    {
        $allowedTypes = array(self::TYPE_URL, self::TYPE_BASE64, self::TYPE_FILE);
        if (!in_array($type, $allowedTypes, true)) {
            throw new InvalidArgumentException('Type '.$type.' is not allowed.');
        }

        $this->type = $type;
        $this->data = $data;
    }

    public static function url($url)
    {
        return new static(static::TYPE_URL, $url);
    }

    public static function base64($base64)
    {
        return new static(static::TYPE_BASE64, $base64);
    }

    public static function file($file)
    {
        return new static(static::TYPE_FILE, $file);
    }

    public function isUrl()
    {
        return $this->type === static::TYPE_URL;
    }

    public function isBase64()
    {
        return $this->type === static::TYPE_BASE64;
    }

    public function isFile()
    {
        return $this->type === static::TYPE_FILE;
    }

    public function getData()
    {
        if ($this->isFile()) {
            return $this->data;
        }

        return $this->data;
    }
}
