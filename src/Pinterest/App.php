<?php

namespace Pinterest;

class Application
{
    /**
     * The client identifier.
     *
     * @var string
     */
    private $clientId;

    /**
     * The client secret.
     *
     * @var string
     */
    private $clientSecret;

    /**
     * The constructor.
     *
     * @param string $clientId     The client identifier.
     * @param string $clientSecret The client secret.
     */
    public function __construct($clientId, $clientSecret)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    private function formatScopes(array $scopes)
    {
        return implode(' ', $scopes);
    }

    public function getAccessToken($code)
    {
        $api = new Api();

        return $api->getAccessToken($code, $this->clientId, $this->clientSecret);
    }

    public function getAuthUrl(array $scopes = [])
    {
        $scopes = $this->formatScopes($scopes);

        $params = [
            'response_type' => 'code',
            'client_id'     => $this->clientId,
            'scope'         => $scopes,
        ];

        return sprintf(
            '%s/oauth/authorize?%s',
            self::APP_URI,
            http_build_query($params)
        );
    }
}
