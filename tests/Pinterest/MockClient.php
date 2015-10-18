<?php

namespace Pinterest\Tests;

use Pinterest\Http\ClientInterface;
use Pinterest\Http\Request;
use Pinterest\Http\Response;
use Pinterest\Authentication as Auth;
use stdClass;

/**
 * This http client mocks responses.
 *
 * The responses are stored as json.
 * If no response is found for a request,
 * the file will be automatically created.
 *
 * @author Hans Ott <hansott@hotmail.be>
 */
class MockClient implements ClientInterface
{
    /**
     * The http client to fallback.
     *
     * @var ClientInterface
     */
    private $http;

    /**
     * The caching directory.
     *
     * @var string
     */
    protected $cacheDir;

    /**
     * Creates a new mocking client.
     *
     * @param ClientInterface $http The fallback client to use.
     */
    public function __construct(ClientInterface $http, $cacheDir)
    {
        $this->http = $http;
        $this->cacheDir = $cacheDir;
    }

    /**
     * Execute an Http request.
     *
     * @param Request $request The Http Request
     *
     * @return Response The Http Response
     */
    public function execute(Request $request)
    {
        return $this->makeResponse($request);
    }

    private static function getPath($url)
    {
        return parse_url($url, PHP_URL_PATH);
    }

    private static function paramsToString($params)
    {
        $hiddenParams = array(
            'image_url',
            'image_base64',
            'image',
            'board'
        );

        foreach ($hiddenParams as $param) {
            if (isset($params[$param])) {
                $params[$param] = 'data';
            }
        }

        return implode('_', $params);
    }

    /**
     * Returns the caching file for a request.
     *
     * @param Request $request The request.
     *
     * @return string The path to the caching file.
     */
    private function getFilePath(Request $request)
    {
        $endpoint = $request->getEndpoint();
        $path = static::getPath($endpoint);
        $method = strtolower($request->getMethod());
        $params = static::paramsToString($request->getParams());
        $file = $method . $path . $params;
        $chars = array('/', ':', '.', ',', ' ');
        $file = static::str_replace($chars, '_', $file);

        return sprintf('%s/%s.json', $this->cacheDir, $file);
    }

    /**
     * Replaces a set of characters with another char in a string.
     *
     * @param array  $chars   The chars to replace.
     * @param string $replace The replacement char.
     * @param string $subject The subject.
     *
     * @return string The string with replaced chars.
     */
    private static function str_replace(array $chars, $replace, $subject)
    {
        foreach ($chars as $char) {
            $subject = str_replace($char, $replace, $subject);
        }

        return $subject;
    }

    /**
     * Encodes a response in json format.
     *
     * @param Response $response The response.
     *
     * @return string The json encoded response.
     */
    private static function encode(Response $response)
    {
        $resp = new stdClass;
        $resp->body = $response->body;
        $resp->statusCode = $response->getStatusCode();
        $resp->headers = $response->getHeaders();

        return json_encode($resp, JSON_PRETTY_PRINT | JSON_FORCE_OBJECT);
    }

    /**
     * Decodes a json encoded response.
     *
     * @param Request $request The request.
     * @param string  $json    The json encoded response.
     *
     * @return Response The response object.
     */
    private static function decode(Request $request, $json)
    {
        $response = json_decode($json);
        $body = (object) $response->body;
        $body = json_encode($body);
        $code = (int) $response->statusCode;
        $headers = (array) $response->headers;

        return new Response($request, $code, $body, $headers);
    }

    /**
     * Writes the response to a json file.
     *
     * @param Request  $request  The request.
     * @param Response $response The response.
     */
    private function writeToFile(Request $request, Response $response)
    {
        $file = $this->getFilePath($request);
        $contents = static::encode($response);
        file_put_contents($file, $contents);
    }

    /**
     * Builds the response object for a stored json response.
     *
     * @param Request $request The request.
     *
     * @return Response The response object.
     */
    private function getFromFile(Request $request)
    {
        $file = $this->getFilePath($request);
        $contents = file_get_contents($file);

        return static::decode($request, $contents);
    }

    /**
     * Checks whether a response exists.
     *
     * @param Request $request The request.
     *
     * @return bool Whether a response exists.
     */
    private function responseExists(Request $request)
    {
        $file = $this->getFilePath($request);

        return file_exists($file);
    }

    /**
     * Makes a response for a request.
     *
     * @param Request $request The request.
     *
     * @return Response The response.
     */
    private function makeResponse(Request $request)
    {
        if ($this->responseExists($request)) {
            return $this->getFromFile($request);
        }

        $response = $this->http->execute($request);

        if ($response->ok()) {
            $this->writeToFile($request, $response);
        } else {
            echo PHP_EOL . 'Request failed:' . PHP_EOL;
            var_dump($request->getEndpoint());
            echo PHP_EOL;
            print_r($response->body);
        }

        return $response;
    }
}
