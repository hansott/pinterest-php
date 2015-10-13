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

    /**
     * The base URL of the API.
     *
     * @param string $baseUri
     */
    public function __construct($baseUri)
    {
        $this->guzzle = new Client([
            'base_uri' => $baseUri,
        ]);
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

    private function addToken(&$params, $token)
    {
        if ($token !== null) {
            $params['access_token'] = $token;
        }
    }

    private function addTokenToHeaders(&$headers, $token)
    {
        if ($token !== null) {
            $headers['Authorization'] = sprintf('BEARER %s', $token);
        }
    }

    private function convertResponse(Request $request, GuzzleResponse $guzzleResponse)
    {
        $statusCode = $guzzleResponse->getStatusCode();
        $rawBody = (string) $guzzleResponse->getBody();

        return new Response($request, $statusCode, $rawBody);
    }

    public function execute(Request $request, $token)
    {
        $method = $request->getMethod();
        $endpoint = $request->getEndpoint();
        $params = $request->getParams();
        $headers = [];

        if ($request->isPost()) {
            $this->addTokenToHeaders($headers, $token);
        } else {
            $this->addToken($params, $token);
        }

        $guzzleResponse = $this->call($method, $endpoint, $params, $headers);

        return $this->convertResponse($request, $guzzleResponse);
    }
}
