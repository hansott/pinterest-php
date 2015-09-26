<?php

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Load environments variables
|--------------------------------------------------------------------------
|
| To keep our access token a secret,
| we'll use dotenv to define our access token in a .env file.
|
*/

try {
    $dotenv = new Dotenv\Dotenv(__DIR__.'/../');
    $dotenv->load();
} catch (InvalidArgumentException $e) {

    /*
     * It's okay to fail here.
     * Because env variables are set with Travis.
     */
}

/*
|--------------------------------------------------------------------------
| Set The Default Timezone
|--------------------------------------------------------------------------
|
| Here we will set the default timezone for PHP. PHP is notoriously mean
| if the timezone is not explicitly set. This will be used by each of
| the PHP date and date-time functions throughout the application.
|
*/

date_default_timezone_set('UTC');
