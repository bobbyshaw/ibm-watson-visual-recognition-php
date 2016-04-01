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

    private $id;
    private $name;
    private $score;

    public function setUp()
    {
        $this->classifier = new Classifier($this->id, $this->name, $this->score);
    }

    public function testGetId()
    {
        $this->assertEquals($this->id, $this->classifier->getId());
    }

    public function testGetName()
    {
        $this->assertEquals($this->name, $this->classifier->getName());
    }

    public function testGetScore()
    {
        $this->assertEquals($this->score, $this->classifier->getScore());
    }
}
