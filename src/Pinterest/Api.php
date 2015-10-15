<?php

namespace Pinterest;

use InvalidArgumentException;
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
     * @var Authentication The authentication client to use.
     */
    private $client;

    /**
     * The constructor.
     *
     * @param Authentication $client The authentication client to use.
     */
    public function __construct(Authentication $client)
    {
        $this->client = $client;
    }

    /**
     * Processes a response.
     *
     * @param Response $response  The response object.
     * @param callable $processor The response processor.
     *
     * @return Response The response.
     */
    private function processResponse(Response $response, $processor)
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
    private function execute(Request $request, $processor = null)
    {
        $response = $this->client->execute($request);

        if (is_callable($processor)) {
            $response = $this->processResponse($response, $processor);
        }

        return $response;
    }

    /**
     * Fetches a single user and processes the response.
     *
     * @return Objects\User The User.
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
     * @return Objects\Board The Board.
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
     * @return PagedList[Objects\Board] A list of Boards.
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
     * @return PagedList[Objects\User] A list of Users.
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
     * @return PagedList[Objects\Pin] A list of Pins.
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
     * @return Objects\User The User.
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
     * @return Objects\Board The Board.
     */
    public function getBoard($id)
    {
        $request = new Request('GET', sprintf('boards/%s', $id));

        return $this->fetchBoard($request);
    }

    /**
     * Returns the boards of the authenticated user.
     *
     * @return PagedList[Objects\Board] A list of Boards.
     */
    public function getUserBoards()
    {
        $request = new Request('GET', 'me/boards');

        return $this->fetchMultipleBoards($request);
    }

    /**
     * Returns the pins of the authenticated user.
     *
     * @return PagedList[Objects\Pin] A list of Likes.
     */
    public function getUserLikes()
    {
        $request = new Request('GET', 'me/likes');

        return $this->fetchMultiplePins($request);
    }

    /**
     * Returns the pins of the authenticated user.
     *
     * @return PagedList[Objects\Pin] A list of Pins.
     */
    public function getUserPins()
    {
        $request = new Request('GET', 'me/pins');

        return $this->fetchMultiplePins($request);
    }

    /**
     * Returns the authenticated user.
     *
     * @return Objects\User The current User.
     */
    public function getCurrentUser()
    {
        $request = new Request('GET', 'me');

        return $this->fetchUser($request);
    }

    /**
     * Returns the followers of the authenticated user.
     *
     * @return PagedList[Objects\User] The current User's followers.
     */
    public function getUserFollowers()
    {
        $request = new Request('GET', 'me/followers');

        return $this->fetchMultipleUsers($request);
    }

    /**
     * Returns the boards that the authenticated user follows.
     *
     * @return PagedList[Objects\Board] The Boards the current user follows.
     */
    public function getUserFollowingBoards()
    {
        $request = new Request('GET', 'me/following/boards');

        return $this->fetchMultipleBoards($request);
    }

    /**
     * Returns the users that the authenticated user follows.
     *
     * @return PagedList[Objects\User] A list of users.
     */
    public function getUserFollowing()
    {
        $request = new Request('GET', 'me/following/users');

        return $this->fetchMultipleUsers($request);
    }

    /**
     * Returns the interests (pins) that the authenticated user follows.
     *
     * @return PagedList[Objects\Pin] The current User's interests.
     */
    public function getUserInterests()
    {
        $request = new Request('GET', 'me/following/interests');

        return $this->fetchMultiplePins($request);
    }

    /**
     * Follows a user.
     *
     * @param string $username The username of the user to follow.
     *
     * @return Http\Response The response.
     */
    public function followUser($username)
    {
        if (empty($username)) {
            throw new InvalidArgumentException('Username is required.');
        }

        $request = new Request(
            'POST',
            'me/following/users/',
            array(
                'user' => (string) $username,
            )
        );

        return $this->execute($request);
    }

    /**
     * Create a Pin
     *
     * @param string $pin The pin to create
     *
     * @return Http\Response The response
     */
    public function createPin($boardId, $note, Image $image, $link = null)
    {
        if (empty($boardId)) {
            throw new InvalidArgumentException('board id should not be empty');
        }

        if (empty($note)) {
            throw new InvalidArgumentException('note should not be empty');
        }

        $params = array(
            'board' => $boardId,
            'note' => $note,
        );

        if (!empty($link)) {
            $params['link'] = $link;
        }

        $imageKey = $image->isUrl() ? 'image_url' : ($image->isBase64() ? 'image_base64' : 'image');
        if ($image->isFile()) {
            $params[$imageKey] = $image;
        } else {
        $params[$imageKey] = $image->getData();
        }

        $request = new Request('POST', 'pins/', $params);

        return $this->execute($request);
    }
}
