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

use Pinterest\App\Scope;
use Pinterest\Authentication;
use Pinterest\Http\BuzzClient;

class AuthenticationTest extends TestCase
{
    public function getHttpClient()
    {
        return new BuzzClient();
    }

    public function testConstructWithAccessToken()
    {
        $accessToken = 'access-token';
        $auth = Authentication::withAccessToken($this->getHttpClient(), 'client-id', 'client-secret', $accessToken);
        $this->assertSame($accessToken, $auth->getAccessToken());
    }

    public function testConstructOnlyAccessToken()
    {
        $accessToken = 'access-token';
        $auth = Authentication::onlyAccessToken($this->getHttpClient(), $accessToken);
        $this->assertSame($accessToken, $auth->getAccessToken());
    }

    public function testGetAuthenticationUrl()
    {
        $auth = new Authentication($this->getHttpClient(), 'client-id', 'client-secret');
        $authUrl = $auth->getAuthenticationUrl('http://localhost', array(Scope::READ_PUBLIC), 'random');
        $excepted = 'https://api.pinterest.com/oauth/?response_type=code&redirect_uri=http%3A%2F%2Flocalhost&client_id=client-id&scope=read_public&state=random';
        $this->assertSame($excepted, $authUrl);

        $this->setExpectedException('Pinterest\Api\Exceptions\InvalidScopeException');
        $auth->getAuthenticationUrl('http://localhost', array('not-valid'), 'random');

        $this->setExpectedException('Pinterest\Api\Exceptions\TooManyScopesGiven');
        $auth->getAuthenticationUrl('http://localhost', array(Scope::READ_PUBLIC, Scope::READ_PUBLIC, Scope::READ_PUBLIC, Scope::READ_PUBLIC, Scope::READ_PUBLIC), 'random');

        $this->setExpectedException('Pinterest\Api\Exceptions\AtLeastOneScopeNeeded');
        $auth->getAuthenticationUrl('http://localhost', array(), 'random');
    }
}
