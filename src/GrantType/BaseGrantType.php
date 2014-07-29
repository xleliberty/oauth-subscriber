<?php

/**
 * Creator: xav
 * Created At: 29/07/2014 - 13:49
 */

namespace GuzzleHttp\Subscriber\Oauth\GrantType;

/**
 * Class BaseGrantType
 * @package GuzzleHttp\Subscriber\Oauth\GrantType
 */
abstract class BaseGrantType {

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
} 