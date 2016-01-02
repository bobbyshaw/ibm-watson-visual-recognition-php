<?php

namespace Bobbyshaw\WatsonVisualRecognition;

/**
 * This class formalises information about a classifier.
 *
 * @author Tom Robertshaw <me@tomrobertshaw.net>
 */
class Classifier
{
    /** @var string */
    private $id;

    /** @var string */
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
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
