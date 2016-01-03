<?php

namespace Bobbyshaw\WatsonVisualRecognition;

/**
 * This class formalises information about a classifier.
 *
 * @api
 * @author Tom Robertshaw <me@tomrobertshaw.net>
 */
class Classifier
{
    /**
     * Classifier ID
     * @var string
     */
    private $id;

    /**
     * Classifier Name
     * @var string
     */
    private $name;

    /**
     * Classification score against image
     * @var float
     */
    private $score;

    /**
     * Classifier constructor.
     *
     * @param string $id
     * @param string $name
     * @param float|null $score
     */
    public function __construct($id, $name, $score = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->score = $score;
    }

    /**
     * Get Classifier ID.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get Classifier name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get classification score.
     *
     * @return float
     */
    public function getScore()
    {
        return $this->score;
    }
}
