<?php

namespace Bobbyshaw\WatsonVisualRecognition\Tests\Message;

use Bobbyshaw\WatsonVisualRecognition\Message\AbstractRequest;
use Bobbyshaw\WatsonVisualRecognition\Tests\Base;
use GuzzleHttp\Client;

/**
 * Class AbstractRequestTest
 * @package Bobbyshaw\WatsonVisualRecognition\Tests\Message
 */
class AbstractRequestTest extends Base
{
    /**
     * @var AbstractRequest
     */
    private $request;
    private $config = ['username' => 'username'];

    public function setUp()
    {
        $this->request = $this->getMockForAbstractClass(AbstractRequest::class, [new Client()]);
        $this->request->initialize($this->config);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testGetResponseBeforeSend()
    {
        $this->request->getResponse();
    }

    public function testGetParameters()
    {
        $this->assertEquals($this->config, $this->request->getParameters());
    }
}
