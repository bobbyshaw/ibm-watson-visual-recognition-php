<?php

namespace Bobbyshaw\WatsonVisualRecognition\Tests\Message;

use Bobbyshaw\WatsonVisualRecognition\Client;
use Bobbyshaw\WatsonVisualRecognition\Message\GetClassifiersRequest;
use Bobbyshaw\WatsonVisualRecognition\Tests\Base;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;

/**
 * Class GetClassifiersRequestTest
 * @package Bobbyshaw\WatsonVisualRecognition\Tests\Message
 */
class GetClassifiersRequestTest extends Base
{
    /** @var Client */
    private $client;
    private $config;
    private $username = 'username';
    private $password = 'password';
    private $version = '2015-12-02';

    public function setUp()
    {
        $this->config = array(
            'username' => $this->username,
            'password' => $this->password,
            'version' => $this->version
        );

        $this->client = new Client();
        $this->client->initialize($this->config);
    }

    /**
     * Test that params are as expected
     */
    public function testGetData()
    {
        /** @var GetClassifiersRequest $request */
        $request = $this->client->getClassifiers();

        $this->assertEquals($this->config, $request->getData());
    }

    /**
     * Test the getClassifiers function HTTP request and response
     */
    public function testGetClassifiers()
    {
        $container = [];
        $guzzle = $this->getMockHttpClientWithHistoryAndResponses(
            $container,
            [$this->getMockHttpResponse('GetClassifiersSuccess.txt')]
        );

        $this->client = new Client($guzzle);
        $this->client->initialize(['username' => $this->username, 'password' => $this->password]);

        /** @var GetClassifiersRequest $request */
        $classifiersRequest = $this->client->getClassifiers();

        $response = $classifiersRequest->send();
        $classifiers = $response->getClassifiers();


        // One request should be sent
        $this->assertCount(1, $container);

        $transaction = $container[0];

        /** @var Request $request */
        $request = $transaction['request'];

        /** @var Uri $uri */
        $uri = $request->getUri();

        // Check method
        $this->assertEquals('GET', $request->getMethod());

        // Check we're talking to the right host
        $this->assertEquals('https', $uri->getScheme());
        $this->assertEquals('gateway.watsonplatform.net', $uri->getHost());

        // Check version query parameter
        $query = \GuzzleHttp\Psr7\parse_query($uri->getQuery());
        $this->assertArrayHasKey('version', $query);

        // Check path
        $this->assertEquals('/visual-recognition-beta/api/v2/classifiers/', $uri->getPath());

        // Check basic auth
        $auth = $request->getHeader('Authorization');
        $this->assertEquals('Basic ' . base64_encode($this->username . ':' . $this->password), $auth[0]);

        $this->assertEquals($response, $classifiersRequest->getResponse());
    }

    /**
     * Test getClassifiers failed auth is handled appropriately
     *
     * @expectedException \Bobbyshaw\WatsonVisualRecognition\Exceptions\AuthException
     */
    public function testGetClassifiersFailedAuthResponse()
    {
        $container = [];
        $response = $this->getMockHttpResponse('FailedAuth.txt', 401);
        $httpClient = $this->getMockHttpClientWithHistoryAndResponses($container, [$response]);

        $this->client = new Client($httpClient);
        $this->client->initialize(['username' => $this->username, 'password' => $this->password]);

        /** @var GetClassifiersRequest $request */
        $request = $this->client->getClassifiers();
        $request->send();
    }
}
