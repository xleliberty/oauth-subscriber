<?php

namespace GuzzleHttp\Subscriber\Oauth;

/**
 * Class AccessToken
 * @package GuzzleHttp\Subscriber\Oauth
 */
class AccessToken
{
    /**
     * @var string
     */
    private $token;

    /**
     * @var \DateTime
     */
    private $expires;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $scope;

    /**
     * @var string
     */
    private $refreshToken;

    /**
     * @param      $token
     * @param      $expiresIn
     * @param      $type
     * @param      $scope
     * @param null $refreshToken
     */
    public function __construct($token, $expiresIn = null, $type = null, $scope = null, $refreshToken = null)
    {
        $this->token   = $token;

        if (null !== $expiresIn) {
            $this->expires = new \DateTime();
            $this->expires->add(new \DateInterval(sprintf('PT%sS', $expiresIn)));
        }

        $this->type         = $type;
        $this->scope        = $scope;
        $this->refreshToken = $refreshToken;
    }

    /**
     * @return \DateTime
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    /**
     * @return bool
     */
    public function isExpired()
    {
        if (null === $this->expires) {
            return false;
        }

        $now = new \DateTime('now');

        return $now > $this->expires;
    }
}
