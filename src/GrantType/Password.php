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
        $this->config->set('grant_type', 'password');
    }
}
