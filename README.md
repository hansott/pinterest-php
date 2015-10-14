# Pinterest PHP SDK [![Build Status](https://img.shields.io/travis/hansott/pinterest-php.svg?style=flat-square)](https://travis-ci.org/hansott/pinterest-php) [![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/hansott/pinterest-php.svg?style=flat-square)](https://scrutinizer-ci.com/g/hansott/pinterest-php/?branch=master) [![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/hansott/pinterest-php.svg?style=flat-square)](https://scrutinizer-ci.com/g/hansott/pinterest-php/?branch=master)

A wrapper library for the Pinterest API.

**Work in progress!**

## Get started

### Installing

`$ composer require hansott/pinterest-php`

### The basics

#### Authentication

To use the API, you need an Access Token from pinterest. [https://developers.pinterest.com/apps/](Create a new Pinterest application) if you haven't already. You then get a client ID and a client secret, specific for that application.

Back in your PHP application, create a Client instance (the default is `GuzzleClient`) and use it to create an Authentication instance:

```php
$client = new Pinterest\Http\GuzzleClient();
$auth = new Pinterest\Authentication($client, $clientId, $clientSecret);
```

Replace the `$clientId` and `$clientSecret` variables with the data of [https://developers.pinterest.com/apps/](your Pinterest application).

You can now let your user authenticate with your application be redirecting them to the URL obtained by a call to `$auth->getAuthenticationUrl()`, like this:

```php
$url = $auth->getAuthenticationUrl(
    'https://your/redirect/url/here',
    array(
        'read_public',
        'write_public',
        'read_relationships',
        'write_relationships',
    ),
    'validation-state-0149281'
);

header ("Location: " . $url);
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

#### Usage
Initialize the Api class:

```php
$auth = Pinterest\Authentication::onlyAccessToken($client, $token);
$api = new Pinterest\Api($auth);
```

Using the `Pinterest\Api` instance in `$api`, you can now make authenticated API requests to Pinterest's API on behalf of the user.

## Contributing

This library uses the PSR-2 coding standard.

To run the unit tests:  
`$ vendor/bin/phpunit`  

(Don't forget to rename the .env-example to .env and set your own credentials)
