<?php

namespace GuzzleHttp\Subscriber\Oauth\GrantType;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Collection;
use GuzzleHttp\Subscriber\Oauth\AccessToken;

class Password extends BaseGrantType implements GrantTypeInterface
{
    public function __construct(ClientInterface $client, array $config)
    {
        $this->client = $client;
        $this->config = Collection::fromConfig(
            $config,
            [
                'client_secret' => '',
                'scope'         => '',
            ],
            ['client_id', 'username', 'password']
        );
    }

    /**
     * @return AccessToken
     */
    public function getToken()
    {
        $body = $this->config->toArray();
        $body['grant_type'] = 'password';
        $response = $this->client->post(null, ['body' => $body]);
        $data = $response->json();

        $refresh_token = isset($data['refresh_token']) ? $data['refresh_token'] : null;

        return new AccessToken(
            $data['access_token'],
            $data['expires_in'],
            $data['token_type'],
            $data['scope'],
            $refresh_token
        );
    }
}
