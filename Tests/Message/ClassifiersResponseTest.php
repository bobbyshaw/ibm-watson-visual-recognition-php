<?php

namespace Bobbyshaw\WatsonVisualRecognition\Tests\Message;

use Bobbyshaw\WatsonVisualRecognition\Classifier;
use Bobbyshaw\WatsonVisualRecognition\Message\ClassifiersResponse;
use Bobbyshaw\WatsonVisualRecognition\Message\RequestInterface;
use Bobbyshaw\WatsonVisualRecognition\Tests\Base;

/**
 * Class ClassifiersResponseTest
 * @package Bobbyshaw\WatsonVisualRecognition\Tests\Message
 */
class ClassifiersResponseTest extends Base
{
    protected $images = array('cosmos-flower-433424_640.jpg', 'hummingbird-1047836_640.jpg');

    protected $classifiers = [
        "Black" => "Black",
        "Blue" => "Blue",
        "Brown" => "Brown",
        "Cyan" => "Cyan",
        "Green" => "Green",
        "Magenta" => "Magenta",
        "Mixed_Color" => "Mixed_Color",
        "Orange" => "Orange",
        "Red" => "Red",
        "Violet" => "Violet",
        "White" => "White",
        "Yellow" => "Yellow",
        "Black_and_white" => "Black_and_white",
        "Color" => "Color",
    ];

    public function testSuccess()
    {
        /** @var RequestInterface $httpRequest */
        $httpRequest = $this->getMockForAbstractClass(RequestInterface::class);
        $httpResponse = $this->getMockHttpResponse('GetClassifiersSuccess.txt');

        $response = new ClassifiersResponse($httpRequest, $httpResponse->getBody());

        $classifiers = $response->getClassifiers();

        $this->assertTrue($response->isSuccessful());
        $this->assertCount(14, $classifiers);

        foreach ($classifiers as $classifier) {
            $this->assertInstanceOf(Classifier::class, $classifier);
            $this->assertContains($classifier->getName(), $this->classifiers);
        }
    }

    /**
     * Test versbose response
     */
    public function testVerbose()
    {
        /** @var RequestInterface $httpRequest */
        $httpRequest = $this->getMockForAbstractClass(RequestInterface::class);
        $httpResponse = $this->getMockHttpResponse('GetClassifiersVerbose.txt');

        $response = new ClassifiersResponse($httpRequest, $httpResponse->getBody());

        $classifiers = $response->getClassifiers();

        $this->assertTrue($response->isSuccessful());
        $this->assertCount(713, $classifiers);

        foreach ($classifiers as $classifier) {
            $this->assertInstanceOf(Classifier::class, $classifier);
            $this->assertInstanceOf(\DateTime::class, $classifier->getCreated());
            $this->assertEquals('IBM', $classifier->getOwner());
        }
    }
}
