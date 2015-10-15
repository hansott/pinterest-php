<?php

namespace Pinterest\Http;

use Pinterest\Image;
use Buzz\Browser;
use Buzz\Client\Curl;
use Buzz\Message\Response as BuzzResponse;
use Buzz\Message\Form\FormRequest;
use Buzz\Message\Form\FormUpload;
use Exception;
use Buzz\Exception\RequestException;

/**
 * The implemented http client class (uses Buzz).
 */
class BuzzClient implements ClientInterface
{
    private $client;

    public function __construct()
    {
        $curl = new Curl();
        $this->client = new Browser($curl);
    }

    private function convertResponse(Request $request, BuzzResponse $buzzResponse)
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
     * Execute an Http request.
     *
     * @param Request $request The Http Request
     *
     * @return Response The Http Response
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

        return $this->convertResponse($request, $buzzResponse);
    }
}
