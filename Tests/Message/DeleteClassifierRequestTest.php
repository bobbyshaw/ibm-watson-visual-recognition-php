<?php

namespace Bobbyshaw\WatsonVisualRecognition\Tests\Message;

use Bobbyshaw\WatsonVisualRecognition\Client;
use Bobbyshaw\WatsonVisualRecognition\Message\DeleteClassifierRequest;
use Bobbyshaw\WatsonVisualRecognition\Message\Response;
use Bobbyshaw\WatsonVisualRecognition\Tests\Base;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response as HttpResponse;
use GuzzleHttp\Psr7\Uri;

/**
 * Class GetClassifiersRequestTest
 * @package Bobbyshaw\WatsonVisualRecognition\Tests\Message
 */
class DeleteClassifierRequestTest extends Base
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
        /** @var DeleteClassifierRequest $request */
        $request = $this->client->deleteClassifier(['classifier_id' => $this->classifierId]);

        $this->assertEquals(array_merge($this->config, ['classifier_id' => $this->classifierId]), $request->getData());
    }

    /**
     * Test the delete classifier function HTTP request
     */
    public function testDeleteClassifier()
    {
        $container = [];
        $guzzle = $this->getMockHttpClientWithHistoryAndResponses(
            $container,
            [new HttpResponse(200, [], '{}')]
        );


        $this->client = new Client($guzzle);
        $this->client->initialize(['username' => $this->username, 'password' => $this->password]);

        /** @var DeleteClassifierRequest $request */
        $classifierRequest = $this->client->deleteClassifier(['classifier_id' => $this->classifierId]);

        /** @var Response $response */
        $classifierRequest->send();

        // One request should be sent
        $this->assertCount(1, $container);

        $transaction = $container[0];

        /** @var Request $request */
        $request = $transaction['request'];

        /** @var Uri $uri */
        $uri = $request->getUri();

        // Check data is passed in as query parameter and not as a post
        $this->assertEquals('DELETE', $request->getMethod());

        // Check path
        $this->assertEquals('/visual-recognition-beta/api/v2/classifiers/' . $this->classifierId, $uri->getPath());
    }

    /**
     * Test deleteClassifier failed auth is handled appropriately
     *
     * @expectedException \Bobbyshaw\WatsonVisualRecognition\Exceptions\AuthException
     */
    public function testDeleteClassifierFailedAuthResponse()
    {
        $container = [];
        $response = $this->getMockHttpResponse('FailedAuth.txt', 401);
        $httpClient = $this->getMockHttpClientWithHistoryAndResponses($container, [$response]);

        $this->client = new Client($httpClient);
        $this->client->initialize(['username' => $this->username, 'password' => $this->password]);

        /** @var DeleteClassifierRequest $request */
        $request = $this->client->deleteClassifier(['classifier_id' => $this->classifierId]);
        $request->send();
    }
}
