<?php

namespace GuzzleHttp\Subscriber\Oauth;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Event\BeforeEvent;
use GuzzleHttp\Event\ErrorEvent;
use GuzzleHttp\Event\RequestEvents;
use GuzzleHttp\Event\SubscriberInterface;
use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Subscriber\Oauth\GrantType\GrantTypeInterface;
use GuzzleHttp\Subscriber\Oauth\GrantType\RefreshToken;
use GuzzleHttp\Exception\BadResponseException;

/**
 * Class Oauth2
 * @package GuzzleHttp\Subscriber\Oauth
 */
class Oauth2 implements SubscriberInterface
{
    /**
     * @var AccessToken
     */
    private $accessToken;

    /**
     * @var GrantTypeInterface
     */
    private $grantType;

    /**
     * @var boolean
     */
    private $useRefreshToken;

    /**
     * @var string
     */
    private $refreshToken;


    /**
     * @param GrantTypeInterface $grantType
     * @param boolean            $useRefreshToken
     */
    public function __construct(GrantTypeInterface $grantType = null, $useRefreshToken = false)
    {
        $this->grantType       = $grantType;
        $this->useRefreshToken = $useRefreshToken;
    }

    /**
     * @inheritdoc
     */
    public function getEvents()
    {
        return [
            'before' => ['onBefore', RequestEvents::SIGN_REQUEST],
            'error'  => ['onError', RequestEvents::EARLY],
        ];
    }

    /**
     * @param BeforeEvent $event
     */
    public function onBefore(BeforeEvent $event)
    {
        $request = $event->getRequest();

        // Only sign requests using "auth"="oauth2"
        if ($request->getConfig()->get('auth') != 'oauth2') {
            return;
        }

        $token = $this->getAccessToken();
        $header = $this->getAuthorizationHeader($token);

        $request->setHeader('Authorization', $header);
    }

    /**
     * @param ErrorEvent $event
     */
    public function onError(ErrorEvent $event)
    {
        if (null !== $event->getResponse() && 401 == $event->getResponse()->getStatusCode()) {
            $request = $event->getRequest();
            if (!$request->getConfig()->get('retried')) {
                if ($this->acquireAccessToken()) {
                    $request->getConfig()->set('retried', true);
                    $this->setHeader($request);
                    $event->intercept($event->getClient()->send($request));
                }
            }
        }
    }

    /**
     * Set access token
     *
     * @param AccessToken $accessToken OAuth2 access token
     */
    public function setAccessToken(AccessToken $accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * Get access token
     * if AccesToken is expired, we try to acquire a new one
     *
     * @return AccessToken Oauth2 access token
     */
    public function getAccessToken()
    {
        if ($this->accessToken instanceof AccessToken && $this->accessToken->isExpired()) {
            $this->accessToken = null;
        }

        if (null === $this->accessToken) {
            $this->acquireAccessToken();
        }

        return $this->accessToken;
    }

    /**
     * @return string
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    /**
     * @param string $refreshToken
     *
     * @return $this
     */
    public function setRefreshToken($refreshToken)
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }


    /**
     * Set the authorization header
     * @param RequestInterface $request
     */
    private function setHeader(RequestInterface $request)
    {
        $token = $this->getAccessToken();
        $header = $this->getAuthorizationHeader($token);
        $request->setHeader('Authorization', $header);
    }

    /**
     * @param AccessToken $token
     *
     * @return string
     */
    private function getAuthorizationHeader(AccessToken $token)
    {
        return sprintf('Bearer %s', $token->getToken());
    }

    /**
     * @return AccessToken
     */
    private function acquireAccessToken()
    {
        $this->accessToken = null;

        if ($this->grantType && $this->useRefreshToken && $this->refreshToken) {
            $this->accessToken = $this->acquireAccessTokenFromRefreshToken();
        }

        if ($this->grantType && null === $this->accessToken) {
            $this->accessToken = $this->grantType->getToken();
            if ($this->useRefreshToken) {
                $this->refreshToken = $this->accessToken->getRefreshToken();
            }
        }

        return $this->accessToken;
    }

    /**
     * @return mixed AccessToken|null
     */
    private function acquireAccessTokenFromRefreshToken()
    {
        $client  = $this->grantType->getClient();
        $config  = $this->grantType->getConfig()->toArray();
        $refresh = new RefreshToken($client, $config);

        try {
            $accessToken        = $refresh->getToken($this->refreshToken);
            $this->refreshToken = $accessToken->getRefreshToken();

            return $accessToken;

        } catch (BadResponseException $e) {

            return null;
        }
    }
}
