# Pinterest PHP

An easy-to-use wrapper for the [Pinterest API](https://developers.pinterest.com/tools/api-explorer/).

<p align="center">
    <img src="banner.png" alt="Pinterest PHP">
</p>

<p align="center">
    <a href="https://travis-ci.org/hansott/pinterest-php"><img src="https://img.shields.io/travis/hansott/pinterest-php.svg?style=flat-square" alt="Build Status"></a>
    <a href="https://scrutinizer-ci.com/g/hansott/pinterest-php/?branch=master"><img src="https://img.shields.io/scrutinizer/g/hansott/pinterest-php.svg?style=flat-square" alt="Scrutinizer Code Quality"></a>
    <a href="https://scrutinizer-ci.com/g/hansott/pinterest-php/?branch=master"><img src="https://img.shields.io/scrutinizer/coverage/g/hansott/pinterest-php.svg?style=flat-square" alt="Code Coverage"></a>
    <a href="https://packagist.org/packages/hansott/pinterest-php"><img src="https://img.shields.io/packagist/v/hansott/pinterest-php.svg?style=flat-square" alt="Packagist"></a>
    <img src="https://img.shields.io/hhvm/hansott/pinterest-php/master.svg?style=flat-square" alt="HHVM">
</p>


## Install

Via Composer

```bash
$ composer require hansott/pinterest-php
```

## Usage

### Authentication

To use the API, you need an Access Token from pinterest. [https://developers.pinterest.com/apps/](Create a new Pinterest application) if you haven't already. You then get a client ID and a client secret, specific for that application.

Back in your PHP application, create a Client instance (the default is `BuzzClient`) and use it to create an Authentication instance:

```php
$client = new Pinterest\Http\BuzzClient();
$auth = new Pinterest\Authentication($client, $clientId, $clientSecret);
```

Replace the `$clientId` and `$clientSecret` variables with the data of [https://developers.pinterest.com/apps/](your Pinterest application).

You can now let your user authenticate with your application be redirecting them to the URL obtained by a call to `$auth->getAuthenticationUrl()`, like this:

```php
use Pinterest\App\Scope;

$url = $auth->getAuthenticationUrl(
    'https://your/redirect/url/here',
    array(
        Scope::READ_PUBLIC,
        Scope::WRITE_PUBLIC,
        Scope::READ_RELATIONSHIPS,
        Scope::WRITE_RELATIONSHIPS,
    ),
    'random-string'
);

header('Location: ' . $url);
exit;
```

- The redirect URL is the URL to the page where pinterest will send us the authentication code for the user registering with your application. This URL needs to be accessible over https, and it has to be filled into to form of your Pinterst application (in the Pinterest backend).
- The second parameter is an array of permissions your app needs on the user's account. There needs to be at least one here.
- The validation state is a random code that you generate for the user registering, and persist (in SESSION for instance). Pinterest will send it back to us for further reference.

When your application user agrees to let your app take control of their Pinterest account via the API, Pinterest will redirect them to the URL you provided as redirect URL, with some added GET parameters. The most important being "code", which we'll trade for an OAuth Access Token in the next step. They'll also send the validation state back to us as a GET parameter so we can check if we expected this call.

The last step in the process is trading that code for an Access Token:

```php
$code = $_GET['code'];
$token = $auth->requestAccessToken($code);
```

You should persist that token safely at this point. You can use it from now on to connect to the Pinterest API from your application, on behalf of the user.

Initialize the Api class:

```php
$auth = Pinterest\Authentication::onlyAccessToken($client, $token);
$api = new Pinterest\Api($auth);
```

Using the `Pinterest\Api` instance in `$api`, you can now make authenticated API requests to Pinterest's API on behalf of the user.

### Get the authenticated user

```php
$response = $api->getCurrentUser();
if ($response->ok()) {
    $user = $response->result(); // $user instanceof Objects\User
}
```

### Get a user

```php
// Get user by username
$response = $api->getUser('otthans');

// Get user by user id
$response = $api->getUser('314196648911734959');

if ($response->ok()) {
    $user = $response->result(); // $user instanceof Objects\User
}
```

### Get a board

```php
$response = $api->getBoard('314196580192594085');
if ($response->ok()) {
    $board = $response->result(); // $board instanceof Objects\Board
}
```

### Update a board

```php
$response = $api->getBoard('314196580192594085');
if (!$response->ok()) {
    die($response->getError());
}

$board = $response->result(); // $board instanceof Objects\Board
$board->name = 'New board name';
$board->description = 'New board description';
$response = $api->updateBoard($board);
if (!$response->ok()) {
    die($response->getError());
}

$updatedBoard = $response->result(); // $updatedBoard instanceof Objects\Board
```

### Get the boards of the authenticated user

```php
$response = $api->getUserBoards();
if ($response->ok()) {
    $pagedList = $response->result(); // $boards instanceof Objects\PagedList
    $boards = $pagedList->items(); // array of Objects\Board objects
}
```

### Get the pins of the authenticated user

```php
$response = $api->getUserLikes();
if ($response->ok()) {
    $pagedList = $response->result(); // $boards instanceof Objects\PagedList
    $pins = $pagedList->items(); // array of Objects\Pin objects
}
```

See [Get the next items of a paged list](#get-the-next-items-of-a-paged-list)

### Get the followers of the authenticated user

```php
$response = $api->getUserFollowers();
if ($response->ok()) {
    $pagedList = $response->result(); // $boards instanceof Objects\PagedList
    $users = $pagedList->items(); // array of Objects\User objects
}
```

See [Get the next items of a paged list](#get-the-next-items-of-a-paged-list)

### Get the boards that the authenticated user follows

```php
$response = $api->getUserFollowingBoards();
if ($response->ok()) {
    $pagedList = $response->result(); // $boards instanceof Objects\PagedList
    $boards = $pagedList->items(); // array of Objects\Board objects
}
```

See [Get the next items of a paged list](#get-the-next-items-of-a-paged-list)

### Get the users that the authenticated user follows

```php
$response = $api->getUserFollowing();
if ($response->ok()) {
    $pagedList = $response->result(); // $boards instanceof Objects\PagedList
    $users = $pagedList->items(); // array of Objects\User objects
}
```

See [Get the next items of a paged list](#get-the-next-items-of-a-paged-list)

### Get the interests that the authenticated user follows

Example: [Modern architecture](https://www.pinterest.com/explore/901179409185)

```php
$response = $api->getUserInterests();
if ($response->ok()) {
    $pagedList = $response->result(); // $boards instanceof Objects\PagedList
    $boards = $pagedList->items(); // array of Objects\Board objects
}
```

See [Get the next items of a paged list](#get-the-next-items-of-a-paged-list)

### Follow a user

```php
$response = $api->followUser('otthans');
if ($response->ok()) {
    // Succeeded
}
```

### Create a board

```php
$name = 'My new board';
$optionalDescription = 'The description of the board';
$response = $api->createBoard($name, $optionalDescription);
if ($response->ok()) {
    $board = $response->result(); // $board instanceof Objects\Board
}
```

### Delete a board

```php
$boardId = '314196580192594085';
$response = $api->createBoard($boardId);
if ($response->ok()) {
    // Succeeded
}
```

### Create a pin

```php
$boardId = '314196580192594085';
$note = 'This is an amazing pin!';
$optionalLink = 'http://hansott.github.io/';

// Load an image from a url.
$image = Pinterest\Image::url('http://lorempixel.com/g/400/200/cats/');

// Load an image from a file.
$pathToFile = 'myfolder/myimage.png';
$image = Pinterest\Image::file($pathToFile);

// Load a base64 encoded image.
$pathToFile = 'myfolder/myimage.png';
$data = file_get_contents($pathToFile);
$base64 = base64_encode($data);
$image = Pinterest\Image::base64($base64);
 
$response = $api->createPin($boardId, $note, $image, $optionalLink);
if ($response->ok()) {
    $pin = $response->result(); // $pin instanceof Objects\Pin
}
```

### Delete a pin

```php
$pinId = 'the-pin-id';
$response = $api->deletePin($pinId);
if ($response->ok()) {
    // Succeeded
}
```

### Get the next items of a paged list

```php
$hasMoreItems = $pagedList->hasNext();
if (!$hasMoreItems) {
    return;
}
$response = $api->getNextItems($pagedList);
if (!$response->ok()) {
    echo $response->getError();
}
$nextPagedList = $response->result();
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email [hansott@hotmail.be](mailto:hansott@hotmail.be) instead of using the issue tracker.

## Credits

- [Hans Ott](https://github.com/hansott)
- [Toon Daelman](https://github.com/turanct)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
