<?php

namespace GuzzleHttp\Subscriber\Oauth\GrantType;

use GuzzleHttp\Subscriber\Oauth\AccessToken;

/**
 * OAuth2 grant type
 */
interface GrantTypeInterface
{
    /**
     * Get access token
     *
     * @return AccessToken
     */
    public function getToken();

    /**
     * Get the guzzle client
     *
     * @return mixed
     */
    public function getClient();

    /**
     * get the config
     *
     * @return mixed
     */
    public function getConfig();
}
