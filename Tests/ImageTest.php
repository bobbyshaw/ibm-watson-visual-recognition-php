<?php

namespace Bobbyshaw\WatsonVisualRecognition\Tests;

use Bobbyshaw\WatsonVisualRecognition\Classifier;
use Bobbyshaw\WatsonVisualRecognition\Image;

/**
 * Class ImageTest
 * @package Bobbyshaw\WatsonVisualRecognition\Tests
 */
class ImageTest extends Base
{
    /** @var Image */
    private $image;

    /** @var Classifier[] */
    private $classifiers;

    private $imageName = 'parrot';


    public function setUp()
    {
        $this->classifiers = [new Classifier('test', 'test', 'test')];
        $this->image = new Image($this->imageName, $this->classifiers);
    }

    public function testGetName()
    {
        $this->assertEquals($this->imageName, $this->image->getName());
    }

    public function testGetClassifiers()
    {
        $this->assertEquals($this->classifiers, $this->image->getClassifiers());
    }

    public function testGetClassifier()
    {
        $this->assertEquals($this->classifiers[0], $this->image->getClassifier('test'));
    }

    public function testGetClassifierNotFound()
    {
        $this->assertEquals(false, $this->image->getClassifier('foobar'));
    }
}
