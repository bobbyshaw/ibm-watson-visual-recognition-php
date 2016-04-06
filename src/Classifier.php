<?php

namespace Bobbyshaw\WatsonVisualRecognition;

use \DateTime;

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
     * Classifier Owner/Creator
     * @var string
     */
    private $owner;

    /**
     * Classifier Creation Date
     *
     * @var \DateTime
     */
    private $created;

    /**
     * Classifier constructor.
     *
     * @param string $id
     * @param string $name
     * @param float|null $score
     * @param string|null $owner
     * @param DateTime|null $created
     */
    public function __construct($id, $name, $score = null, $owner = null, $created = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->score = $score;
        $this->owner = $owner;
        $this->created = $created;
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
     * @return float|null
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Get Owner
     *
     * @return null|string
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Get Creation Date
     *
     * @return DateTime|null
     */
    public function getCreated()
    {
        return $this->created;
    }
}
