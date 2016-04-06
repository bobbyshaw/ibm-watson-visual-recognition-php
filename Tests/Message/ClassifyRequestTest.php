<?php

namespace Bobbyshaw\WatsonVisualRecognition\Tests\Message;

use Bobbyshaw\WatsonVisualRecognition\Client;
use Bobbyshaw\WatsonVisualRecognition\Message\ClassifyRequest;
use Bobbyshaw\WatsonVisualRecognition\Message\RequestInterface;
use Bobbyshaw\WatsonVisualRecognition\Tests\Base;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

/**
 * Class ClassifyRequestTest
 * @package Bobbyshaw\WatsonVisualRecognition\Tests\Message
 */
class ClassifyRequestTest extends Base
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /** @var array $container */
    protected $container = [];

    /**
     * Create a mock Guzzle Client with example response
     */
    public function setUp()
    {
        parent::setUp();

        $request = $this->getMockHttpResponse('ClassifySuccess.txt');
        $httpClient = $this->getMockHttpClientWithHistoryAndResponses($this->container, [$request]);

        $this->request = new ClassifyRequest($httpClient);

        $this->request->initialize(
            [
                'images_file' => 'Tests/images/hummingbird-1047836_640.jpg',
                'classifier_ids' => [1, 2, 3],
            ]
        );
    }

    /**
     * Check for expected image and classifier IDs
     */
    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame('Tests/images/hummingbird-1047836_640.jpg', $data['images_file']);
        $this->assertSame([1, 2, 3], $data['classifier_ids']);
    }

    /**
     * Test that the Classify POST HTTP Request is sent
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
        $this->assertEquals('/visual-recognition-beta/api/v2/classify/', $uri->getPath());

        // Check post data
        $this->assertStringStartsWith('multipart/form-data', $httpRequest->getHeaderLine('Content-Type'));

        $body = $httpRequest->getBody()->getContents();

        $imageData = "Content-Disposition: form-data; name=\"images_file\"; filename=\"hummingbird-1047836_640.jpg\"";
        $this->assertContains($imageData, $body);

        $classifierIdsData = "{\"classifiers\":[{\"classifier_id\":1},{\"classifier_id\":2},{\"classifier_id\":3}]}";
        $this->assertContains($classifierIdsData, $body);
    }

    /**
     * Check that exception is thrown if incorrect file type provided
     *
     * @expectedException \InvalidArgumentException
     */
    public function testDisallowedFileType()
    {
        $request = $this->getMockHttpResponse('ClassifySuccess.txt');
        $httpClient = $this->getMockHttpClientWithHistoryAndResponses($this->container, [$request]);

        $this->request = new ClassifyRequest($httpClient);

        $this->request->initialize(['images_file' => 'Tests/images/hummingbird-1047836_640.tom']);

        $this->request->send();
    }

    /**
     * Test getClassifiers failed auth is handled appropriately
     *
     * @expectedException \InvalidArgumentException
     */
    public function testIncorrectFileApiResponse()
    {
        $container = [];
        $response = new Response(415, [], '{"code":415,"error":"Unsupported image type"}');
        $httpClient = $this->getMockHttpClientWithHistoryAndResponses($container, [$response]);

        $client = new Client($httpClient);
        $client->initialize(['username' => 'test', 'password' => 'test']);

        /** @var ClassifyRequest $request */
        $request = $client->classify(['images_file' => 'Tests/images/hummingbird-1047836_640.jpg']);
        $request->send();
    }

    /**
     * Test failed auth is handled appropriately
     *
     * @expectedException \Bobbyshaw\WatsonVisualRecognition\Exceptions\AuthException
     */
    public function testFailedAuth()
    {
        $container = [];
        $response = $this->getMockHttpResponse('FailedAuth.txt', 401);
        $httpClient = $this->getMockHttpClientWithHistoryAndResponses($container, [$response]);

        $client = new Client($httpClient);
        $client->initialize(['username' => 'test', 'password' => 'test']);

        /** @var ClassifyRequest $request */
        $request = $client->classify(['images_file' => 'Tests/images/hummingbird-1047836_640.jpg']);
        $request->send();
    }
}
