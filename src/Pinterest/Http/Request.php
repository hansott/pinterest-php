<?php

namespace Pinterest\Http;

class Request implements RequestInterface
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
     * The constructor.
     *
     * @param string $method   The http method.
     * @param string $endpoint The relative url to call.
     * @param array  $params   The parameters.
     */
    public function __construct($method, $endpoint, $params = [])
    {
        $this->method = $method;
        $this->endpoint = $endpoint;
        $this->params = $params;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getEndpoint()
    {
        return $this->endpoint;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function isPost()
    {
        return strtolower($this->method) === 'post';
    }

    public function isGet()
    {
        return strtolower($this->method) === 'get';
    }
}
