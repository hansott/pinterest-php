<?php

namespace Pinterest\Http;

use Buzz\Browser;
use Buzz\Client\Curl;
use Buzz\Exception\RequestException;
use Buzz\Message\Form\FormRequest;
use Buzz\Message\Form\FormUpload;
use Buzz\Message\Response as BuzzResponse;
use Exception;
use Pinterest\Image;

/**
 * The implemented http client class (uses Buzz).
 *
 * @link https://github.com/kriswallsmith/Buzz
 *
 * @author Toon Daelman <spinnewebber_toon@hotmail.com>
 */
class BuzzClient implements ClientInterface
{
    /**
     * Buzz browser.
     *
     * @var Buzz\Browser
     */
    private $client;

    /**
     * Creates a new buzz client.
     */
    public function __construct()
    {
        $curl = new Curl();
        $this->client = new Browser($curl);
    }

    /**
     * Converts a buzz response to a pinterest response.
     *
     * @param  Request      $request      The request.
     * @param  BuzzResponse $buzzResponse The buzz response.
     *
     * @return Response The response.
     */
    private static function convertResponse(Request $request, BuzzResponse $buzzResponse)
    {
        $statusCode = $buzzResponse->getStatusCode();
        $rawBody = (string) $buzzResponse->getContent();

        $rawHeaders = $buzzResponse->getHeaders();
        $headers = array();
        foreach ($rawHeaders as $header) {
            if (stristr($header, 'HTTP/1.')) {
                continue;
            }

            list($key, $value) = explode(': ', $header);

            $headers[$key] = $value;
        }

        return new Response($request, $statusCode, $rawBody, $headers);
    }

    /**
     * Executes a http request.
     *
     * @param Request $request The http request.
     *
     * @return Response The http response.
     */
    public function execute(Request $request)
    {
        $method = $request->getMethod();
        $endpoint = $request->getEndpoint();
        $params = $request->getParams();
        $headers = $request->getHeaders();

        try {
            if ($method == 'GET') {
                $buzzResponse = $this->client->call(
                    $endpoint . '?' . http_build_query($params),
                    $method,
                    $headers,
                    array()
                );
            } else {
                $buzzRequest = new FormRequest();
                $buzzRequest->fromUrl($endpoint);
                $buzzRequest->setMethod($method);
                $buzzRequest->setHeaders($headers);
                foreach($params as $key => $value) {
                    if ($value instanceof Image) {
                        $value = new FormUpload($value->getData());
                    }

                    $buzzRequest->setField($key, $value);
                }

                $buzzResponse = new BuzzResponse();
                $this->client->send($buzzRequest, $buzzResponse);
            }
        } catch (RequestException $e) {
            throw new Exception($e->getMessage());
        }

        return static::convertResponse($request, $buzzResponse);
    }
}
