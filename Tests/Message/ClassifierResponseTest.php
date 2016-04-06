<?php

namespace Bobbyshaw\WatsonVisualRecognition\Tests\Message;

use Bobbyshaw\WatsonVisualRecognition\Classifier;
use Bobbyshaw\WatsonVisualRecognition\Message\ClassifierResponse;
use Bobbyshaw\WatsonVisualRecognition\Message\RequestInterface;
use Bobbyshaw\WatsonVisualRecognition\Tests\Base;
use DateTime;

/**
 * Class ClassifierResponseTest
 * @package Bobbyshaw\WatsonVisualRecognition\Tests\Message
 */
class ClassifierResponseTest extends Base
{

    public function testSuccess()
    {
        /** @var RequestInterface $httpRequest */
        $httpRequest = $this->getMockForAbstractClass(RequestInterface::class);
        $httpResponse = $this->getMockHttpResponse('GetClassifierSuccess.txt');

        $response = new ClassifierResponse($httpRequest, $httpResponse->getBody());

        /** @var Classifier $classifier */
        $classifier = $response->getClassifier();

        $this->assertEquals('Magenta', $classifier->getName());
        $this->assertEquals('Magenta', $classifier->getId());
        $this->assertNull($classifier->getScore());
        $this->assertEquals('IBM', $classifier->getOwner());
        $this->assertEquals(new DateTime('2016-03-24T14:19:16.000Z'), $classifier->getCreated());
    }
}
