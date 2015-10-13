<?php

namespace Pinterest\Http;

/**
 * The request class.
 *
 * @author Hans Ott <hansott@hotmail.be>
 */
final class Request
{
    /**
     * The http method.
     *
     * @var string
     */
    private $method;

    /**
     * The endpoint to call.
     *
     * Relative url.
     *
     * @var string
     */
    private $endpoint;

    /**
     * The parameters to pass.
     *
     * @var array
     */
    private $params;

    /**
     * The headers to pass.
     *
     * @var array
     */
     private $headers;

    /**
     * The constructor.
     *
     * @param string $method   The http method.
     * @param string $endpoint The relative url to call.
     * @param array  $params   The parameters.
     * @param array  $headers  The headers.
     */
    public function __construct($method, $endpoint, array $params = [], array $headers = [])
    {
        $this->method = (string) $method;
        $this->endpoint = (string) $endpoint;
        $this->params = $params;
        $this->headers = $headers;
    }

    /**
     * Get the Http method
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Get the Http endpoint
     *
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * Get the Http parameters
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Get the Http headers
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Is this a POST request?
     *
     * @return bool
     */
    public function isPost()
    {
        return strtolower($this->method) === 'post';
    }

    /**
     * Is this a GET request?
     *
     * @return bool
     */
    public function isGet()
    {
        return strtolower($this->method) === 'get';
    }
}
