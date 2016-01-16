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

if (!defined('JSON_PRETTY_PRINT')) {
    define('JSON_PRETTY_PRINT', 128);
}

require __DIR__.'/../../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Load environments variables
|--------------------------------------------------------------------------
|
| To keep our credentials a secret,
| we'll use dotenv to store them in a .env file.
|
*/

try {
    $dotenv = new Dotenv\Dotenv(__DIR__.'/../../');
    $dotenv->load();
} catch (InvalidArgumentException $e) {
    // It's okay to fail here. Because env variables are set with Travis.
}

/*
|--------------------------------------------------------------------------
| Set The Default Timezone
|--------------------------------------------------------------------------
|
| Here we will set the default timezone for PHP. PHP is notoriously mean
| if the timezone is not explicitly set.
|
*/

date_default_timezone_set('UTC');
