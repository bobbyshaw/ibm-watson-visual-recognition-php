<?php

namespace Bobbyshaw\WatsonVisualRecognition\Tests\Message;

use Bobbyshaw\WatsonVisualRecognition\Message\AbstractRequest;
use Bobbyshaw\WatsonVisualRecognition\Message\AbstractResponse;
use Bobbyshaw\WatsonVisualRecognition\Tests\Base;
use GuzzleHttp\Client;

/**
 * Class AbstractResponseTest
 * @package Bobbyshaw\WatsonVisualRecognition\Tests\Message
 */
class AbstractResponseTest extends Base
{
    /**
     * @var AbstractRequest
     */
    protected $request;

    /**
     * @var AbstractResponse
     */
    protected $response;

    protected $data = 'test';

    public function setUp()
    {
        $this->request = $this->getMockForAbstractClass(AbstractRequest::class, [new Client()]);
        $this->response = $this->getMockForAbstractClass(AbstractResponse::class, [$this->request, $this->data]);
    }
    
    public function testGetRequest()
    {
        $this->assertEquals($this->request, $this->response->getRequest());
    }

    public function testGetData()
    {
        $this->assertEquals($this->data, $this->response->getData());
    }
}
