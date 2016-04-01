<?php

namespace Bobbyshaw\WatsonVisualRecognition\Tests\Message;

use Bobbyshaw\WatsonVisualRecognition\Message\ClassifyRequest;
use Bobbyshaw\WatsonVisualRecognition\Message\RequestInterface;
use Bobbyshaw\WatsonVisualRecognition\Tests\Base;
use GuzzleHttp\Psr7\Request;

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

    /** @var [] $container */
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
}
