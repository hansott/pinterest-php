<?php

namespace Pinterest;

use InvalidArgumentException;
use Pinterest\Http\Request;
use Pinterest\Http\Response;
use Pinterest\Http\Exceptions\RateLimited;
use Pinterest\Objects\Board;
use Pinterest\Objects\User;
use Pinterest\Objects\Pin;

/**
 * The api client.
 *
 * @author Hans Ott <hansott@hotmail.be>
 * @author Toon Daelman <spinnewebber_toon@hotmail.com>
 */
class Api
{
    /**
     *  The authentication client.
     *
     * @var Authentication
     */
    private $auth;

    /**
     * The constructor.
     *
     * @param Authentication $client The authentication client.
     */
    public function __construct(Authentication $auth)
    {
        $this->auth = $auth;
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
    public function execute(Request $request, $processor = null)
    {
        $response = $this->auth->execute($request);

        if ($response->rateLimited()) {
            throw new RateLimitedReached($response);
        }

        if (is_callable($processor)) {
            $response = $this->processResponse($response, $processor);
        }

        return $response;
    }

    /**
     * Fetches a single user and processes the response.
     *
     * @return Http\Response The response.
     */
    private function fetchUser(Request $request)
    {
        $request->setFields(User::fields());

        return $this->execute($request, function (Response $response) {
            $mapper = new Mapper(new User());

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
        $request->setFields(Board::fields());

        return $this->execute($request, function (Response $response) {
            $mapper = new Mapper(new Board());

            return $mapper->toSingle($response);
        });
    }

    /**
     * Fetches a single pin and processes the response.
     *
     * @return Http\Response The response.
     */
    private function fetchPin(Request $request)
    {
        $request->setFields(Pin::fields());

        return $this->execute($request, function (Response $response) {
            $mapper = new Mapper(new Pin());

            return $mapper->toSingle($response);
        });
    }

    /**
     * Fetches multiple boards and processes the response.
     *
     * @return Http\Response The response.
     */
    private function fetchMultipleBoards(Request $request, array $fields = null)
    {
        $fields = $fields ? $fields : Board::fields();
        $request->setFields($fields);

        return $this->execute($request, function (Response $response) {
            $mapper = new Mapper(new Board());

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
        $request->setFields(User::fields());

        return $this->execute($request, function (Response $response) {
            $mapper = new Mapper(new User());

            return $mapper->toList($response);
        });
    }

    /**
     * Fetches multiple pins and processes the response.
     *
     * @param $fields array The fields to require.
     *
     * @return Http\Response The response.
     */
    private function fetchMultiplePins(Request $request, array $fields = null)
    {
        $fields = $fields ? $fields : Pin::fields();
        $request->setFields($fields);

        return $this->execute($request, function (Response $response) {
            $mapper = new Mapper(new Pin());

            return $mapper->toList($response);
        });
    }

    /**
     * Returns a single user.
     *
     * @param $usernameOrId string The username or identifier of the user.
     *
     * @return Objects\User The user.
     */
    public function getUser($usernameOrId)
    {
        if (empty($usernameOrId)) {
            throw new InvalidArgumentException('The username or id should not be empty.');
        }

        $request = new Request('GET', sprintf('users/%s/', $usernameOrId));

        return $this->fetchUser($request);
    }

    /**
     * Returns a board by identifier.
     *
     * @param string $id The board identifier.
     *
     * @return Objects\Board The board.
     */
    public function getBoard($id)
    {
        $request = new Request('GET', sprintf('boards/%s/', $id));

        return $this->fetchBoard($request);
    }

    /**
     * Returns the boards of the authenticated user.
     *
     * @return PagedList[Objects\Board] A list of boards.
     */
    public function getUserBoards()
    {
        $request = new Request('GET', 'me/boards/');

        return $this->fetchMultipleBoards($request);
    }

    /**
     * Returns the pins of the authenticated user.
     *
     * @return PagedList[Objects\Pin] A list of likes.
     */
    public function getUserLikes()
    {
        $request = new Request('GET', 'me/likes/');

        return $this->fetchMultiplePins($request);
    }

    /**
     * Returns the pins of the authenticated user.
     *
     * @return PagedList[Objects\Pin] A list of pins.
     */
    public function getUserPins()
    {
        $request = new Request('GET', 'me/pins/');

        return $this->fetchMultiplePins($request);
    }

    /**
     * Returns the authenticated user.
     *
     * @return Objects\User The authenticated user.
     */
    public function getCurrentUser()
    {
        $request = new Request('GET', 'me/');

        return $this->fetchUser($request);
    }

    /**
     * Returns the followers of the authenticated user.
     *
     * @return PagedList[Objects\User] The current User's followers.
     */
    public function getUserFollowers()
    {
        $request = new Request('GET', 'me/followers/');

        return $this->fetchMultipleUsers($request);
    }

    /**
     * Returns the boards that the authenticated user follows.
     *
     * @return PagedList[Objects\Board] The Boards the current user follows.
     */
    public function getUserFollowingBoards()
    {
        $request = new Request('GET', 'me/following/boards/');

        return $this->fetchMultipleBoards($request);
    }

    /**
     * Returns the users that the authenticated user follows.
     *
     * @return PagedList[Objects\User] A list of users.
     */
    public function getUserFollowing()
    {
        $request = new Request('GET', 'me/following/users/');

        return $this->fetchMultipleUsers($request);
    }

    /**
     * Return the Interests the logged in user follows.
     *
     * @link https://www.pinterest.com/explore/901179409185
     *
     * @return PagedList[Objects\Board] The user's interests.
     */
    public function getUserInterests()
    {
        $request = new Request('GET', 'me/following/interests/');

        return $this->fetchMultipleBoards($request, array('id', 'name'));
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
     * Creates a Pin.
     *
     * @param string $pin The pin to create.
     *
     * @return Http\Response The response.
     */
    public function createPin($boardId, $note, Image $image, $link = null)
    {
        if (empty($boardId)) {
            throw new InvalidArgumentException('The board id should not be empty.');
        }

        if (empty($note)) {
            throw new InvalidArgumentException('The note should not be empty.');
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

        return $this->fetchPin($request);
    }

    /**
     * Deletes a Pin.
     *
     * @param string $pinId The id of the pin to delete.
     *
     * @return Http\Response The response.
     */
    public function deletePin($pinId)
    {
        if (empty($pinId)) {
            throw new InvalidArgumentException('The pin id should not be empty.');
        }

        $request = new Request('DELETE', sprintf('pins/%d/', $pinId));

        return $this->execute($request);
    }
}
