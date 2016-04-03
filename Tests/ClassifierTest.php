<?php

namespace Bobbyshaw\WatsonVisualRecognition\Tests;

use Bobbyshaw\WatsonVisualRecognition\Classifier;
use DateTime;

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
    private $classifierOwner = 'IBM';
    private $classifierCreated = '2016-03-24T14:19:16.000Z';

    public function setUp()
    {
        $this->classifier = new Classifier(
            $this->classifierId,
            $this->classifierName,
            $this->classifierScore,
            $this->classifierOwner,
            new DateTime($this->classifierCreated)
        );
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

    public function testGetOwner()
    {
        $this->assertEquals($this->classifierOwner, $this->classifier->getOwner());
    }

    public function testGetCreated()
    {
        $this->assertEquals(new DateTime($this->classifierCreated), $this->classifier->getCreated());
    }
}
