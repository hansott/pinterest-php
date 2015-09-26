<?php

namespace Pinterest;

use Pinterest\Api\Exceptions\TokenMissing;
use Pinterest\Http\CacheInterface as Cache;
use Pinterest\Http\ClientInterface as Client;
use Pinterest\Http\GuzzleClient;
use Pinterest\Http\Request;
use Pinterest\Http\Response;

class Api
{
    /**
     * The API base uri.
     *
     * @var string
     */
    const BASE_URI = 'https://api.pinterest.com/v1/';

    /**
     * The access token to use.
     *
     * @var string
     */
    private $token;

    /**
     * The http client to use.
     *
     * @var Http\ClientInterface
     */
    private $client;

    /**
     * The constructor.
     *
     * @param string $token The access token to use.
     */
    public function __construct($token)
    {
        $this->token = $token;
        $this->client = new GuzzleClient(self::BASE_URI);
    }

    /**
     * Sets the given client as http client.
     *
     * @return self
     */
    public function setClient(Client $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Checks if an access token is set.
     *
     * @return bool Whether an access token is set.
     */
    private function hasToken()
    {
        return !empty($this->token);
    }

    /**
     * Processes a response.
     *
     * @param Response $response  The response object.
     * @param callable $processor The response processor.
     *
     * @return Response The response.
     */
    private function processResponse(Response &$response, callable $processor)
    {
        if ($response->ok()) {
            $result = $processor($response);
            $response->setResult($result);
        }
    }

    /**
     * Executes the given http request.
     *
     * @return Http\Response The response.
     */
    private function execute(Request $request, callable $processor = null)
    {
        if ($this->hasToken()) {
            $response = $this->client->execute($request, $this->token);
            if (is_callable($processor)) {
                $this->processResponse($response, $processor);
            }

            return $response;
        } else {
            throw new TokenMissing();
        }
    }

    /**
     * Sets the given access token as token to use.
     *
     * @param string $token The access token to use.
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Returns a single user.
     *
     * @param $usernameOrId string The username or identifier of the user.
     *
     * @return Http\Response The response.
     */
    public function getUser($usernameOrId)
    {
        $endpoint = sprintf('users/%s', $usernameOrId);
        $request = new Request('GET', $endpoint);

        return $this->fetchUser($request);
    }

    /**
     * Fetches a single user and processes the response.
     *
     * @return Http\Response The response.
     */
    private function fetchUser(Request $request)
    {
        return $this->execute($request, function (Response $response) {
            $mapper = new Mapper(new Objects\User());

            return $mapper->toSingle($response);
        });
    }

    /**
     * Returns the current user.
     *
     * @return Http\Response The response.
     */
    public function getCurrentUser()
    {
        $request = new Request('GET', 'me');

        return $this->fetchUser($request);
    }
}
