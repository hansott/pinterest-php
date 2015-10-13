<?php

namespace Pinterest\Http;

/**
 * All requests classes must implement this.
 *
 * @author Hans Ott <hansott@hotmail.be>
 */
interface RequestInterface
{
    /**
     * Get the Http method
     *
     * @return string
     */
    public function getMethod();

    /**
     * Get the Http endpoint
     *
     * @return string
     */
    public function getEndpoint();

    /**
     * Get the Http parameters
     *
     * @return array
     */
    public function getParams();

    /**
     * Get the Http headers
     *
     * @return array
     */
    public function getHeaders();

    /**
     * Is this a POST request?
     *
     * @return bool
     */
    public function isPost();

    /**
     * Is this a GET request?
     *
     * @return bool
     */
    public function isGet();
}
