<?php

namespace Bobbyshaw\WatsonVisualRecognition;

/**
 * Image response for classification request
 *
 * @api
 * @author Tom Robertshaw <me@tomrobertshaw.net>
 */
class Image
{
    /**
     * Image Name
     * @var string
     */
    private $name;

    /**
     * Classidiers
     * @var double
     */
    private $classifiers;

    /**
     * Classifier constructor.
     *
     * @param string $name
     * @param array $classifiers
     */
    public function __construct($name, array $classifiers)
    {
        $this->name = $name;
        $this->classifiers = $classifiers;
    }

    /**
     * Get image name
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
     * @return Classifier[]
     */
    public function getClassifiers()
    {
        return $this->classifiers;
    }

    /**
     * Get classifier by ID
     * @param $id Classifier ID
     * @return Classifier
     */
    public function getClassifier($id)
    {
        /** @var Classifier $classifier */
        foreach ($this->classifiers as $classifier) {
            if ($classifier->getId() == $id) {
                return $classifier;
            }
        }
    }
}
