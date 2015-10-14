<?php

namespace Pinterest\Http;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface as GuzzleResponse;

/**
 * The implemented http client class (uses guzzle).
 *
 * @author Hans Ott <hansott@hotmail.be>
 */
class GuzzleClient implements ClientInterface
{
    private $guzzle;

    public function __construct()
    {
        $this->guzzle = new Client();
    }

    private function call($method, $endpoint, array $params, array $headers)
    {
        try {
            return $this->guzzle->request($method, $endpoint, [
                'query'   => $params,
                'headers' => $headers,
            ]);
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                return $e->getResponse();
            } else {
                throw new Exception($e->getMessage());
            }
        }
    }

    private function convertResponse(Request $request, GuzzleResponse $guzzleResponse)
    {
        $statusCode = $guzzleResponse->getStatusCode();
        $rawBody = (string) $guzzleResponse->getBody();

        $headers = $guzzleResponse->getHeaders();
        $headers = array_map(
            function($value) {
                return implode(' ', $value);
            },
            $headers
        );

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

        $guzzleResponse = $this->call($method, $endpoint, $params, $headers);

        return $this->convertResponse($request, $guzzleResponse);
    }
}
