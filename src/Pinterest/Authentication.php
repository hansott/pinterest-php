<?php

namespace Pinterest;

use Pinterest\Http\ClientInterface;
use Pinterest\Http\Request;
use Pinterest\Api\Exceptions\TokenMissing;
use Pinterest\Api\Exceptions\InvalidScopeException;
use Pinterest\Api\Exceptions\AtLeastOneScopeNeeded;
use Pinterest\Api\Exceptions\TooManyScopesGiven;

final class Authentication implements ClientInterface
{
    /**
     * The API base uri.
     *
     * @var string
     */
    const BASE_URI = 'https://api.pinterest.com/v1/';

    private $httpClient;
    private $clientId;
    private $clientSecret;
    private $accessToken;

    /**
     * Constructor
     *
     * @param ClientInterface $client       The (un-authenticated) Http client
     * @param string          $clientId     The client id
     * @param string          $clientSecret The client secret
     */
    public function __construct(ClientInterface $client, $clientId, $clientSecret) 
    {
        $this->httpClient = $client;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    /**
     * Alternative constructor for when we already have an accessToken
     *
     * @param ClientInterface $client       The (un-authenticated) Http client
     * @param string          $clientId     The client id
     * @param string          $clientSecret The client secret
     * @param string          $accessToken  The OAuth access token
     */
    public static function withAccessToken(
        ClientInterface $client,
        $clientId,
        $clientSecret,
        $accessToken
    ) {
        $authentication = new static($client, $clientId, $clientSecret);
        $authentication->accessToken = $accessToken;

        return $authentication;
    }

    /**
     * Alternative constructor for when we only have an accessToken
     *
     * ATTENTION: only the execute method will work, as the others need client id and secret
     *
     * @param ClientInterface $client       The (un-authenticated) Http client
     * @param string          $accessToken  The OAuth access token
     */
    public static function onlyAccessToken(
        ClientInterface $client,
        $accessToken
    ) {
        $authentication = new static($client, null, null);
        $authentication->accessToken = $accessToken;

        return $authentication;
    }

    /**
     * First step of the OAuth process
     *
     * @param string $redirectUrl The OAuth redirect url (where code gets sent)
     * @param array  $scopes      An array of scopes (see assertValidScopes)
     * @param string $state       A state you can use to check if this is a valid redirect
     *
     * @return string The redirect url
     */
    public function getAuthenticationUrl($redirectUrl, array $scopes, $state)
    {
        $this->assertValidScopes($scopes);

        $url = 'https://api.pinterest.com/oauth/?';
        $params = array(
            'response_type' => 'code',
            'redirect_uri' => (string) $redirectUrl,
            'client_id' => $this->clientId,
            'scope' => implode(',', $scopes),
            'state' => (string) $state,
        );

        return $url . http_build_query($params);
    }

    /**
     * Check if an array of given scopes contains only valid scopes (and at least one)
     *
     * @param array $scopes The array of scopes to check
     *
     * @throws InvalidScopeException when invalid scope in the given array
     * @throws AtLeastOneScopeNeeded when no scopes given
     * @throws TooManyScopesGiven when double scopes in the list
     */
    private function assertValidScopes(array $scopes)
    {
        $allowedScopes = array(
            'read_public',
            'write_public',
            'read_relationships',
            'write_relationships',
        );

        foreach ($scopes as $scope) {
            if (!in_array($scope, $allowedScopes)) {
                throw new InvalidScopeException($scope);
            }
        }

        if (count($scopes) < 1) {
            throw new AtLeastOneScopeNeeded();
        }

        if (count($scopes) > count($allowedScopes)) {
            throw new TooManyScopesGiven();
        }
    }

    /**
     * Second step of the OAuth process
     *
     * @param string $code The OAuth code, caught from the redirect page
     *
     * @return string The OAuth access token
     */
    public function requestAccessToken($code)
    {
        $request = new Request(
            'POST',
            static::BASE_URI . 'oauth/token',
            array(
                'grant_type' => 'authorization_code',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'code' => $code,
            )
        );

        $response = $this->httpClient->execute($request);

        if (
            !isset($response->body)
            || !isset($response->body->access_token)
        ) {
            throw new TokenMissing();
        }

        $this->accessToken = $response->body->access_token;

        return $this->accessToken;
    }

    /**
     * Execute an authenticated Http request
     *
     * @param Request $request The Http Request
     *
     * @return Response The Http Response
     */
    public function execute(Request $request)
    {
        $headers = $request->getHeaders();
        $headers['Authorization'] = sprintf('BEARER %s', $this->accessToken);

        $authenticatedRequest = new Request(
            $request->getMethod(),
            static::BASE_URI . $request->getEndpoint(),
            $request->getParams(),
            $headers
        );

        return $this->httpClient->execute($authenticatedRequest);
    }

    /**
     * Get the access token for persisting in some storage
     *
     * @return string The OAuth access token
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }
}
