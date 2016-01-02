<?php

namespace Bobbyshaw\WatsonVisualRecognition;

/**
 * This class formalises information about a classifier.
 *
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
     * Classifier constructor.
     *
     * @param string $id
     * @param string $name
     */
    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * Get Classifier ID
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
}
