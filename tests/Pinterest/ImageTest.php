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

namespace Pinterest\Tests;

use Pinterest\Image;
use PHPUnit_Framework_TestCase;

class ImageTest extends PHPUnit_Framework_TestCase
{
    public function test_it_can_be_instantiated_with_an_url()
    {
        $url = 'http://example.com/example.png';
        $image = Image::url($url);

        $this->assertTrue($image->isUrl());
        $this->assertSame($url, $image->getData());

        $this->assertFalse($image->isBase64());
        $this->assertFalse($image->isFile());
    }

    public function test_it_can_be_instantiated_with_a_base64_string()
    {
        $string = 'base-64-string-test';
        $image = Image::base64($string);

        $this->assertTrue($image->isBase64());
        $this->assertSame($string, $image->getData());

        $this->assertFalse($image->isUrl());
        $this->assertFalse($image->isFile());
    }

    public function test_it_can_be_instantiated_with_a_file_path()
    {
        $path = 'file-path-test';
        $image = Image::file($path);

        $this->assertTrue($image->isFile());
        $this->assertSame($path, $image->getData());

        $this->assertFalse($image->isBase64());
        $this->assertFalse($image->isUrl());
    }
}
