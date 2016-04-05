<?php

namespace Bobbyshaw\WatsonVisualRecognition\Tests\Message;

use Bobbyshaw\WatsonVisualRecognition\Client;
use Bobbyshaw\WatsonVisualRecognition\Message\CreateClassifierRequest;
use Bobbyshaw\WatsonVisualRecognition\Message\RequestInterface;
use Bobbyshaw\WatsonVisualRecognition\Tests\Base;
use GuzzleHttp\Psr7\Request;

/**
 * Class CreateClassifierRequestTest
 * @package Bobbyshaw\WatsonVisualRecognition\Tests\Message
 */
class CreateClassifierRequestTest extends Base
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /** @var [] $container */
    protected $container = [];

    /**
     * Create a mock Guzzle Client with example response
     */
    public function setUp()
    {
        parent::setUp();

        $request = $this->getMockHttpResponse('CreateClassifierSuccess.txt');
        $httpClient = $this->getMockHttpClientWithHistoryAndResponses($this->container, [$request]);

        $this->request = new CreateClassifierRequest($httpClient);

        $this->request->initialize(
            [
                'positive_examples' => 'Tests/images/butterfly-positive.zip',
                'negative_examples' => 'Tests/images/butterfly-negative.zip',
                'name' => 'butterfly'
            ]
        );
    }

    /**
     * Check for expected data
     */
    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame('Tests/images/butterfly-positive.zip', $data['positive_examples']);
        $this->assertSame('Tests/images/butterfly-negative.zip', $data['negative_examples']);
        $this->assertSame('butterfly', $data['name']);
    }

    /**
     * Test that the Classifier POST HTTP Request is sent
     * and contains the correct data
     */
    public function testSendData()
    {
        $this->request->send();

        // Count number of requests sent
        $this->assertEquals(1, count($this->container));

        $transaction = $this->container[0];

        /** @var Request $httpRequest */
        $httpRequest = $transaction['request'];

        $uri = $httpRequest->getUri();

        $this->assertEquals('POST', $httpRequest->getMethod());

        // Check path
        $this->assertEquals('/visual-recognition-beta/api/v2/classifiers/', $uri->getPath());

        // Check post data
        $this->assertStringStartsWith('multipart/form-data', $httpRequest->getHeaderLine('Content-Type'));

        $body = $httpRequest->getBody()->getContents();

        $positiveData = 'Content-Disposition: form-data; name="positive_examples"; filename="butterfly-positive.zip"';
        $this->assertContains($positiveData, $body);

        $negativeData = 'Content-Disposition: form-data; name="negative_examples"; filename="butterfly-negative.zip"';
        $this->assertContains($negativeData, $body);

        $nameData = '/\bbutterfly\b/';

        $this->assertRegExp($nameData, $body);
    }


    /**
     * Test createClassifier failed auth is handled appropriately
     *
     * @expectedException \Bobbyshaw\WatsonVisualRecognition\Exceptions\AuthException
     */
    public function testCreateClassifierFailedAuthResponse()
    {
        $container = [];
        $response = $this->getMockHttpResponse('FailedAuth.txt', 401);
        $httpClient = $this->getMockHttpClientWithHistoryAndResponses($container, [$response]);

        $client = new Client($httpClient);
        $client->initialize(['username' => 'test', 'password' => 'test']);

        /** @var CreateClassifierRequest $request */
        $request = $client->createClassifier($this->request->getData());
        $request->send();
    }

    /**
     * Pass incorrect file types to request to
     *
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidData()
    {
        $client = new Client();
        $client->initialize(['username' => 'test', 'password' => 'test']);

        /** @var CreateClassifierRequest $request */
        $request = $client->createClassifier(
            [
                'positive_examples' => 'test.log',
                'negative_examples' => 'test.log',
                'name' => 'test',
            ]
        );

        $request->send();
    }
}
