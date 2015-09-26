<?php

session_start();

require __DIR__.'/../vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__.'/../');
$dotenv->load();

$app = new Pinterest\App(getenv('CLIENT_ID'), getenv('CLIENT_SECRET'));

$accessToken = isset($_SESSION['access_token']) ? $_SESSION['access_token'] : null;

if ($accessToken !== null) {
    $api = new Pinterest\App($accessToken);
    $response = $api->getUser();
    $user = $response->result();

    echo 'Hi, '.htmlentities($user->name).'!';
} elseif (isset($_GET['code'])) {
    $response = $app->getAccessToken($_GET['code']);
    $_SESSION['access_token'] = $response->body->access_token;
} elseif ($accessToken === null) {
    header(sprintf('Location: %s', $app->getAuthUrl()));
    exit();
}
