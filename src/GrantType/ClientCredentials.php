<?php

namespace GuzzleHttp\Subscriber\Oauth\GrantType;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Collection;
use GuzzleHttp\Subscriber\Oauth\AccessToken;

/**
 * Class ClientCredentials
 * @package GuzzleHttp\Subscriber\Oauth\GrantType
 */
class ClientCredentials extends BaseGrantType implements GrantTypeInterface
{
    /**
     * @param ClientInterface $client
     * @param                 $config
     */
    public function __construct(ClientInterface $client, $config)
    {
        $this->client       = $client;
        $this->config = Collection::fromConfig(
            $config,
            [
                'client_secret' => '',
                'scope'         => '',
            ],
            ['client_id']
        );
        $this->config->set('grant_type', 'client_credentials');
    }
}
