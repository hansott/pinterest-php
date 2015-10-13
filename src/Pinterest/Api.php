<?php

namespace Pinterest;

use InvalidArgumentException;
use Pinterest\Api\Exceptions\TokenMissing;
use Pinterest\Http\ClientInterface as Client;
use Pinterest\Http\GuzzleClient;
use Pinterest\Http\Request;
use Pinterest\Http\Response;
use Pinterest\Objects\Board;
use Pinterest\Objects\User;

/**
 * The api client.
 *
 * @author Hans Ott <hansott@hotmail.be>
 */
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
    private function processResponse(Response $response, callable $processor)
    {
        if ($response->ok()) {
            $result = $processor($response);
            $response->setResult($result);
        }

        return $response;
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
                $response = $this->processResponse($response, $processor);
            }

            return $response;
        } else {
            throw new TokenMissing();
        }
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
     * Fetches a single board and processes the response.
     *
     * @return Http\Response The response.
     */
    private function fetchBoard(Request $request)
    {
        return $this->execute($request, function (Response $response) {
            $mapper = new Mapper(new Objects\Board());

            return $mapper->toSingle($response);
        });
    }

    /**
     * Fetches multiple boards and processes the response.
     *
     * @return Http\Response The response.
     */
    private function fetchMultipleBoards(Request $request)
    {
        return $this->execute($request, function (Response $response) {
            $mapper = new Mapper(new Objects\Board());

            return $mapper->toList($response);
        });
    }

    /**
     * Fetches multiple users and processes the response.
     *
     * @return Http\Response The response.
     */
    private function fetchMultipleUsers(Request $request)
    {
        return $this->execute($request, function (Response $response) {
            $mapper = new Mapper(new Objects\User());

            return $mapper->toList($response);
        });
    }

    /**
     * Fetches multiple boards and processes the response.
     *
     * @return Http\Response The response.
     */
    private function fetchMultiplePins(Request $request)
    {
        return $this->execute($request, function (Response $response) {
            $mapper = new Mapper(new Objects\Pin());

            return $mapper->toList($response);
        });
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
     * Returns a board by identifier.
     *
     * @param string $id The board identifier.
     *
     * @return Http\Response The response.
     */
    public function getBoard($id)
    {
        $request = new Request('GET', sprintf('boards/%s', $id));

        return $this->fetchBoard($request);
    }

    /**
     * Returns the boards of the authenticated user.
     *
     * @return Http\Response The response.
     */
    public function getUserBoards()
    {
        $request = new Request('GET', 'me/boards');

        return $this->fetchMultipleBoards($request);
    }

    /**
     * Returns the pins of the authenticated user.
     *
     * @return Http\Response The response.
     */
    public function getUserLikes()
    {
        $request = new Request('GET', 'me/likes');

        return $this->fetchMultiplePins($request);
    }

    /**
     * Returns the pins of the authenticated user.
     *
     * @return Http\Response The response.
     */
    public function getUserPins()
    {
        $request = new Request('GET', 'me/pins');

        return $this->fetchMultiplePins($request);
    }

    /**
     * Returns the authenticated user.
     *
     * @return Http\Response The response.
     */
    public function getCurrentUser()
    {
        $request = new Request('GET', 'me');

        return $this->fetchUser($request);
    }

    /**
     * Returns the followers of the authenticated user.
     *
     * @return Http\Response The response.
     */
    public function getUserFollowers()
    {
        $request = new Request('GET', 'me/followers');

        return $this->fetchMultipleUsers($request);
    }

    /**
     * Returns the boards that the authenticated user follows.
     *
     * @return Http\Response The response.
     */
    public function getUserFollowingBoards()
    {
        $request = new Request('GET', 'me/following/boards');

        return $this->fetchMultipleBoards($request);
    }

    /**
     * Returns the users that the authenticated user follows.
     *
     * @return Http\Response The response.
     */
    public function getUserFollowing()
    {
        $request = new Request('GET', 'me/following/users');

        return $this->fetchMultipleUsers($request);
    }

    /**
     * Returns the interests (pins) that the authenticated user follows.
     *
     * @return Http\Response The response.
     */
    public function getUserInterests()
    {
        $request = new Request('GET', 'me/following/interests');

        return $this->fetchMultiplePins($request);
    }

    /**
     * Follows a user.
     *
     * @param User $user The user to follow.
     *
     * @return Http\Response The response.
     */
    public function followUser(User $user)
    {
        if (empty($user->username)) {
            throw new InvalidArgumentException('Username is required.');
        }

        $request = new Request('POST', 'me/following/users', [
            'user' => $user->username,
        ]);

        return $this->execute($request);
    }
}
