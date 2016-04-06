<?php

namespace Bobbyshaw\WatsonVisualRecognition\Tests\Message;

use Bobbyshaw\WatsonVisualRecognition\Client;
use Bobbyshaw\WatsonVisualRecognition\Message\AbstractRequest;
use Bobbyshaw\WatsonVisualRecognition\Message\Response;
use Bobbyshaw\WatsonVisualRecognition\Tests\Base;
use GuzzleHttp\Client as HttpClient;

class ResponseTest extends Base
{

    /**
     * @var AbstractRequest
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;


    public function setUp()
    {
        parent::setUp();

        $data = file_get_contents('Tests/Mock/ErrorResponse.txt');

        $this->request = $this->getMockForAbstractClass(AbstractRequest::class, [new HttpClient()]);
        $this->response = new Response($this->request, $data);
    }

    public function testResponseIsNotSuccessful()
    {
        $this->assertEquals(false, $this->response->isSuccessful());
    }

    public function testErrorCode()
    {
        $this->assertEquals(400, $this->response->getErrorCode());
    }

    public function testErrorMessage()
    {
        $this->assertEquals(
            'Upload problem - check that all fields were specified and upload size was less than 100MB',
            $this->response->getErrorMessage()
        );
    }

    public function testNoError()
    {
        /** @var \GuzzleHttp\Psr7\Response $response */
        $response = $this->getMockHttpResponse('GetClassifiersSuccess.txt');

        $this->response = new Response($this->request, $response->getBody()->getContents());

        $this->assertEquals(false, $this->response->getErrorCode());
        $this->assertEquals('', $this->response->getErrorMessage());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testInitialisingRequestAfterResponse()
    {
        $container = [];
        $guzzle = $this->getMockHttpClientWithHistoryAndResponses(
            $container,
            [$this->getMockHttpResponse('GetClassifiersSuccess.txt')]
        );

        $client = new Client($guzzle);
        $client->initialize(['username' => 'test', 'password' => 'test']);

        $request = $client->getClassifiers();
        $request->send();

        $request->initialize();
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testSetUsernameOnRequestAfterResponse()
    {
        $container = [];
        $guzzle = $this->getMockHttpClientWithHistoryAndResponses(
            $container,
            [$this->getMockHttpResponse('GetClassifiersSuccess.txt')]
        );

        $client = new Client($guzzle);
        $client->initialize(['username' => 'test', 'password' => 'test']);

        $request = $client->getClassifiers();
        $request->send();

        $request->setUsername('test2');
    }
}
