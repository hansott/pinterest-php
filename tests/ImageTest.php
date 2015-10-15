<?php

namespace Pinterest;

use PHPUnit_Framework_TestCase;

class ImageTest extends PHPUnit_Framework_TestCase
{
    function test_it_can_be_instantiated_with_an_url()
    {
        $url = 'http://example.com/example.png';
        $image = Image::url($url);

        $this->assertTrue($image->isUrl());
        $this->assertEquals($url, $image->getData());

        $this->assertFalse($image->isBase64());
        $this->assertFalse($image->isFile());
    }

    function test_it_can_be_instantiated_with_a_base64_string()
    {
        $string = 'base-64-string-test';
        $image = Image::base64($string);

        $this->assertTrue($image->isBase64());
        $this->assertEquals($string, $image->getData());

        $this->assertFalse($image->isUrl());
        $this->assertFalse($image->isFile());
    }

    function test_it_can_be_instantiated_with_a_file_path()
    {
        $path = 'file-path-test';
        $image = Image::file($path);

        $this->assertTrue($image->isFile());
        $this->assertEquals($path, $image->getData());

        $this->assertFalse($image->isBase64());
        $this->assertFalse($image->isUrl());
    }
}
