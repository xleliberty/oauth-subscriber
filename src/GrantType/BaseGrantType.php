<?php

/**
 * Creator: xav
 * Created At: 29/07/2014 - 13:49
 */

namespace GuzzleHttp\Subscriber\Oauth\GrantType;

use GuzzleHttp\Subscriber\Oauth\AccessToken;

/**
 * Class BaseGrantType
 * @package GuzzleHttp\Subscriber\Oauth\GrantType
 */
abstract class BaseGrantType {

    /**
     * @var string
     */
    protected $grantType;

    /**
     * @var \GuzzleHttp\ClientInterfaceClientInterface
     */
    protected $client;

    /**
     * @var \GuzzleHttp\Collection
     */
    protected $config;


    /**
     * @return \GuzzleHttp\ClientInterfaceClientInterface
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return \GuzzleHttp\Collection
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param null $refreshToken
     *
     * @return AccessToken
     */
    public function getToken($refreshToken = null)
    {
        $body                  = $this->config->toArray();
        $body['grant_type']    = $this->config->get('grant_type');

        if ('refresh_token' === $this->config->get('grant_type')) {
            $body['refresh_token'] = $refreshToken ?: $this->config['refresh_token'];
        }

        $response = $this->client->post(null, ['body' => $body]);

        return $this->parseTokenData($response);
    }

    /**
     * parse accestoken datas from guzzle Response
     * @param $response
     *
     * @return AccessToken
     */
    private function parseTokenData($response)
    {
        $data     = $response->json();
        $expected = ['access_token', 'expires_in', 'token_type', 'scope', 'refresh_token'];
        foreach ($expected as $var) {
            if (!isset($data[$var])) {
                $data[$var] = null;
            }
        }

        return new AccessToken(
            $data['access_token'],
            $data['expires_in'],
            $data['token_type'],
            $data['scope'],
            $data['refresh_token']
        );
    }
} 