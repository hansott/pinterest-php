<?php

/*
 * This file is part of the Pinterest PHP library.
 *
 * (c) Hans Ott <hansott@hotmail.be>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.md.
 *
 * Source: https://github.com/hansott/pinterest-php
 */

namespace Pinterest\Http;

/**
 * The request class.
 *
 * @author Hans Ott <hansott@hotmail.be>
 * @author Toon Daelman <spinnewebber_toon@hotmail.com>
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
    public function __construct($method, $endpoint, array $params = array(), array $headers = array())
    {
        $this->method = strtoupper((string) $method);
        $this->endpoint = (string) $endpoint;
        $this->params = $params;
        $this->headers = $headers;
    }

    /**
     * Sets the fields.
     *
     * @param array $fields The fields to return.
     *
     * @return Request The current Request instance.
     */
    public function setFields(array $fields)
    {
        $merge = array(
            'fields' => implode(',', $fields),
        );
        $this->params = array_replace($this->params, $merge);

        return $this;
    }

    /**
     * Get the http (lowercase) method.
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Get the http endpoint.
     *
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * Get the http parameters.
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Get the http headers.
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
        return $this->getMethod() === 'POST';
    }

    /**
     * Is this a GET request?
     *
     * @return bool
     */
    public function isGet()
    {
        return $this->getMethod() === 'GET';
    }
}
