<?php

namespace Pinterest\Http;

use Pinterest\Http\Exceptions\MalformedJson;

/**
 * The response class.
 *
 * @author Hans Ott <hansott@hotmail.be>
 */
final class Response
{
    /**
     * The parsed response body.
     *
     * @var mixed
     */
    public $body;

    /**
     * The response status code.
     *
     * @var int
     */
    private $statusCode;

    /**
     * The raw response body.
     *
     * @var string
     */
    private $rawBody;

    /**
     * The HTTP headers.
     *
     * @var array
     */
    private $headers;

    /**
     * The processed result.
     *
     * @var mixed
     */
    private $result;

    /**
     * The request object.
     *
     * @var Request
     */
    private $request;

    /**
     * The constructor.
     *
     * @param Request $request    The request object.
     * @param int     $statusCode The status code.
     * @param string  $rawBody    The raw response body.
     * @param array   $headers    A key => value representation of response headers
     */
    public function __construct(Request $request, $statusCode, $rawBody, array $headers)
    {
        $this->request = $request;
        $this->statusCode = (int) $statusCode;
        $this->rawBody = (string) $rawBody;
        $this->body = $this->parseJson($this->rawBody);
        $this->headers = $headers;
    }

    /**
     * Checks if the response is okay.
     *
     * @return bool Whether the response is okay.
     */
    public function ok()
    {
        return
            !isset($this->body->error)
            && $this->statusCode >= 200
            && $this->statusCode < 300;
    }

    /**
     * Checks if the response is rate-limited.
     *
     * @return bool Whether the response is rate-limited.
     */
    public function rateLimited()
    {
        return $this->statusCode == 429;
    }

    /**
     * Parses the response json in php data.
     *
     * @param string $rawBody The raw response body.
     * @param bool   $toArray Return as array?
     *
     * @return mixed The parsed json.
     */
    private function parseJson($rawBody, $toArray = false)
    {
        $json = json_decode($rawBody, $toArray);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $json;
        } else {
            throw new MalformedJson();
        }
    }

    /**
     * Returns the processed result.
     *
     * @return mixed The processed result.
     */
    public function result()
    {
        return $this->result;
    }

    /**
     * Sets the processed result.
     *
     * @param mixed $result The result.
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Gets the request object.
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Get Headers.
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }
}
