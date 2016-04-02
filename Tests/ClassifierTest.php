<?php

namespace Bobbyshaw\WatsonVisualRecognition\Tests;

use Bobbyshaw\WatsonVisualRecognition\Classifier;

/**
 * Class ClassifierTest
 * @package Bobbyshaw\WatsonVisualRecognition\Tests
 */
class ClassifierTest extends Base
{
    /** @var Classifier */
    private $classifier;

    private $classifierId = '1';
    private $classifierName = 'test';
    private $classifierScore = 0.9;

    public function setUp()
    {
        $this->classifier = new Classifier($this->classifierId, $this->classifierName, $this->classifierScore);
    }

    public function testGetId()
    {
        $this->assertEquals($this->classifierId, $this->classifier->getId());
    }

    public function testGetName()
    {
        $this->assertEquals($this->classifierName, $this->classifier->getName());
    }

    public function testGetScore()
    {
        $this->assertEquals($this->classifierScore, $this->classifier->getScore());
    }
}
