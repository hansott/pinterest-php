<?php

namespace Pinterest\Http;

use Buzz\Browser;
use Buzz\Client\Curl;
use Buzz\Message\Response as BuzzResponse;
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
            $buzzResponse = $this->client->call(
                $endpoint . '?' . http_build_query($params),
                $method,
                $headers,
                array()
            );
        } catch (RequestException $e) {
            throw new Exception($e->getMessage());
        }

        return $this->convertResponse($request, $buzzResponse);
    }
}
