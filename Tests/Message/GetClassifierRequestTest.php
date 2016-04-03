<?php

namespace Bobbyshaw\WatsonVisualRecognition\Tests\Message;

use Bobbyshaw\WatsonVisualRecognition\Client;
use Bobbyshaw\WatsonVisualRecognition\Message\ClassifierResponse;
use Bobbyshaw\WatsonVisualRecognition\Message\GetClassifierRequest;
use Bobbyshaw\WatsonVisualRecognition\Tests\Base;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;

/**
 * Class GetClassifiersRequestTest
 * @package Bobbyshaw\WatsonVisualRecognition\Tests\Message
 */
class GetClassifierRequestTest extends Base
{
    /** @var Client */
    private $client;
    private $config;
    private $username = 'username';
    private $password = 'password';
    private $version = '2015-12-02';
    private $classifierId = 'Magenta';

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
        /** @var GetClassifierRequest $request */
        $request = $this->client->getClassifier(['classifier_id' => $this->classifierId]);

        $this->assertEquals(array_merge($this->config, ['classifier_id' => $this->classifierId]), $request->getData());
    }

    /**
     * Test the getClassifier function HTTP request
     */
    public function testGetClassifier()
    {
        $container = [];
        $guzzle = $this->getMockHttpClientWithHistoryAndResponses(
            $container,
            [$this->getMockHttpResponse('GetClassifierSuccess.txt')]
        );

        $this->client = new Client($guzzle);
        $this->client->initialize(['username' => $this->username, 'password' => $this->password]);

        /** @var GetClassifierRequest $request */
        $classifierRequest = $this->client->getClassifier(['classifier_id' => $this->classifierId]);

        /** @var ClassifierResponse $response */
        $response = $classifierRequest->send();
        $response->getClassifier();


        // One request should be sent
        $this->assertCount(1, $container);

        $transaction = $container[0];

        /** @var Request $request */
        $request = $transaction['request'];

        /** @var Uri $uri */
        $uri = $request->getUri();

        // Check data is passed in as query parameter and not as a post
        $this->assertEquals('GET', $request->getMethod());

        // Check path
        $this->assertEquals('/visual-recognition-beta/api/v2/classifiers/' . $this->classifierId, $uri->getPath());
    }

    /**
     * Test getClassifier failed auth is handled appropriately
     *
     * @expectedException \Bobbyshaw\WatsonVisualRecognition\Exceptions\AuthException
     */
    public function testGetClassifierFailedAuthResponse()
    {
        $container = [];
        $response = $this->getMockHttpResponse('FailedAuth.txt', 401);
        $httpClient = $this->getMockHttpClientWithHistoryAndResponses($container, [$response]);

        $this->client = new Client($httpClient);
        $this->client->initialize(['username' => $this->username, 'password' => $this->password]);

        /** @var GetClassifierRequest $request */
        $request = $this->client->getClassifier(['classifier_id' => $this->classifierId]);
        $request->send();
    }
}
