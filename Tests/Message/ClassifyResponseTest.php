<?php

namespace Bobbyshaw\WatsonVisualRecognition\Tests\Message;

use Bobbyshaw\WatsonVisualRecognition\Classifier;
use Bobbyshaw\WatsonVisualRecognition\Image;
use Bobbyshaw\WatsonVisualRecognition\Message\ClassifyResponse;
use Bobbyshaw\WatsonVisualRecognition\Message\RequestInterface;
use Bobbyshaw\WatsonVisualRecognition\Tests\Base;

/**
 * Class ClassifyResponseTest
 * @package Bobbyshaw\WatsonVisualRecognition\Tests\Message
 */
class ClassifyResponseTest extends Base
{
    protected $images = array('cosmos-flower-433424_640.jpg', 'hummingbird-1047836_640.jpg');

    public function testSuccess()
    {
        /** @var RequestInterface $httpRequest */
        $httpRequest = $this->getMockForAbstractClass(RequestInterface::class);
        $httpResponse = $this->getMockHttpResponse('ClassifySuccess.txt');

        $response = new ClassifyResponse($httpRequest, $httpResponse->getBody());

        $images = $response->getImages();

        $this->assertTrue($response->isSuccessful());
        $this->assertCount(2, $images);

        foreach ($images as $image) {
            $this->assertInstanceOf(Image::class, $image);
            $this->assertContains($image->getName(), $this->images);

            $classifiers = $image->getClassifiers();

            foreach ($classifiers as $classifier) {
                $this->assertInstanceOf(Classifier::class, $classifier);
            }
        }
    }
}
