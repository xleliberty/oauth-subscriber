<?php

namespace GuzzleHttp\Subscriber\Oauth\GrantType;

use GuzzleHttp\Subscriber\Oauth\AccessToken;
use GuzzleHttp\Collection;

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
     * @return Collection
     */
    public function getConfig();
}
