=======================
Guzzle OAuth Subscriber
=======================

Signs HTTP requests using OAuth 1.0. Requests are signed using a consumer key,
consumer secret, OAuth token, and OAuth secret.

Installing
==========

This project can be installed using Composer. Add the following to your
composer.json:

.. code-block:: javascript

    {
        "require": {
            "guzzlehttp/oauth-subscriber": "0.1.0"
        }
    }



Using the Subscriber for oauth1
===============================

Here's an example showing how to send an authenticated request to the Twitter
REST API:

.. code-block:: php

    use GuzzleHttp\Client;
    use GuzzleHttp\Subscriber\Oauth\Oauth1;

    $client = new Client(['base_url' => 'http://api.twitter.com/1.1/statuses/home_timeline.json']);

    $oauth = new Oauth1([
        'consumer_key'    => 'my_key',
        'consumer_secret' => 'my_secret',
        'token'           => 'my_token',
        'token_secret'    => 'my_token_secret'
    ]);

    $client->getEmitter()->attach($oauth);

    // Set the "auth" request option to "oauth" to sign using oauth
    $res = $client->get(null, ['auth' => 'oauth']);

You can set the ``auth`` request option to ``oauth`` for all requests sent by
the client using the client's ``defaults`` constructor option.

.. code-block:: php

    use GuzzleHttp\Client;

    $client = new Client([
        'base_url' => 'http://api.twitter.com/1.1',
        'defaults' => ['auth' => 'oauth']
    ]);

    $client->getEmitter()->attach($oauth);

    // Now you don't need to add the auth parameter
    $res = $client->get('statuses/home_timeline.json');

.. note::

    You can omit the token and token_secret options to use two-legged OAuth.


Using the subscriber for oauth2 authentication
==============================================

Here is an exemple of oauth2 implementation base on php oauth2 server without refresh token

Twitter doesn't use refresh token

.. code-block:: php

    use GuzzleHttp\Client;
    use GuzzleHttp\Subscriber\Oauth\Oauth2;
    use GuzzleHttp\Subscriber\Oauth\GrantType\ClientCredentials;

    $grantType  = new ClientCredentials(
        new Client(['base_url' => 'https://api.twitter.com/oauth2/token']),
        [
            'consumer_key'    => 'my_key',
            'consumer_secret' => 'my_secret'
        ]
    );
    $oauth2_subscriber = new Oauth2($grantType);

    $client = new Client(['base_url' => 'https://api.twitter.com/1.1/statuses/user_timeline.json']);
    $client->getEmitter()->attach($oauth2_subscriber);


    $res = $client->get(null,[
        'auth' => 'oauth2',
        'query' =>
            [
                'count' =>2,
                'screen_name'=>'ladygaga'
            ]
    ]);