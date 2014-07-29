<?php

namespace GuzzleHttp\Subscriber\Oauth\GrantType;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Collection;
use GuzzleHttp\Subscriber\Oauth\AccessToken;

/**
 * Class RefreshToken
 * @package GuzzleHttp\Subscriber\Oauth\GrantType
 */
class RefreshToken extends BaseGrantType implements GrantTypeInterface
{
    /**
     * @param ClientInterface $client
     * @param                 $config
     */
    public function __construct(ClientInterface $client, $config)
    {
        $this->client = $client;
        $this->config = Collection::fromConfig(
            $config,
            [
                'client_secret' => '',
                'refresh_token' => '',
                'scope'         => '',
            ],
            ['client_id']
        );
    }

    /**
     * @param null $refreshToken
     *
     * @return AccessToken
     */
    public function getToken($refreshToken = null)
    {
        $body                  = $this->config->toArray();
        $body['grant_type']    = 'refresh_token';
        $body['refresh_token'] = $refreshToken ?: $this->config['refresh_token'];

        $response = $this->client->post(null, ['body' => $body]);
        $data     = $response->json();

        return new AccessToken(
            $data['access_token'],
            $data['expires_in'],
            $data['token_type'],
            $data['scope'],
            $data['refresh_token']
        );
    }
}
