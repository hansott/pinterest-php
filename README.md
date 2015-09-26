# Pinterest PHP SDK [![Build Status](https://img.shields.io/travis/hansott/pinterest-php.svg?style=flat-square)](https://travis-ci.org/hansott/pinterest-php) [![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/hansott/pinterest-php.svg?style=flat-square)](https://scrutinizer-ci.com/g/hansott/pinterest-php/?branch=master) [![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/hansott/pinterest-php.svg?style=flat-square)](https://scrutinizer-ci.com/g/hansott/pinterest-php/?branch=master)

A wrapper class to communicate with the Pinterest API.

**Work in progress!**

## Get started

### Installing

`$ composer require hansott/pinterest-php`

### The basics

Initialize the Api class:

```php
$api = new Pinterest\Api('your-access-token');
```

## Contributing

This library uses the PSR-2 coding standard.

To run the unit tests:  
`$ vendor/bin/phpunit`  

(Don't forget to rename the .env-example to .env and set your own credentials)
