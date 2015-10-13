<?php

use Pinterest\App\Scope;
use Pinterest\Authentication;
use Pinterest\Http\GuzzleClient;

class AuthenticationTest extends TestCase
{
    public function getHttpClient()
    {
        return new GuzzleClient();
    }

    public function testConstructWithAccessToken()
    {
        $accessToken = 'access-token';
        $auth = Authentication::withAccessToken($this->getHttpClient(), 'client-id', 'client-secret', $accessToken);
        $this->assertEquals($accessToken, $auth->getAccessToken());
    }

    public function testConstructOnlyAccessToken()
    {
        $accessToken = 'access-token';
        $auth = Authentication::onlyAccessToken($this->getHttpClient(), $accessToken);
        $this->assertEquals($accessToken, $auth->getAccessToken());
    }

    public function testGetAuthenticationUrl()
    {
        $auth = new Authentication($this->getHttpClient(), 'client-id', 'client-secret');
        $authUrl = $auth->getAuthenticationUrl('http://localhost', [Scope::READ_PUBLIC], 'random');
        $excepted = 'https://api.pinterest.com/oauth/?response_type=code&redirect_uri=http%3A%2F%2Flocalhost&client_id=client-id&scope=read_public&state=random';
        $this->assertEquals($excepted, $authUrl);

        $this->setExpectedException('Pinterest\Api\Exceptions\InvalidScopeException');
        $auth->getAuthenticationUrl('http://localhost', ['not-valid'], 'random');

        $this->setExpectedException('Pinterest\Api\Exceptions\TooManyScopesGiven');
        $auth->getAuthenticationUrl('http://localhost', [Scope::READ_PUBLIC, Scope::READ_PUBLIC, Scope::READ_PUBLIC, Scope::READ_PUBLIC, Scope::READ_PUBLIC], 'random');

        $this->setExpectedException('Pinterest\Api\Exceptions\AtLeastOneScopeNeeded');
        $auth->getAuthenticationUrl('http://localhost', [], 'random');
    }
}
