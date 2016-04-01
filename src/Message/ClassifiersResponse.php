<?php

namespace Bobbyshaw\WatsonVisualRecognition\Message;

use Bobbyshaw\WatsonVisualRecognition\Classifier;

/**
 * Class ClassifiersResponse
 * @package Bobbyshaw\WatsonVisualRecognition\Message
 */
class ClassifiersResponse extends Response
{
    /**
     * @var Classifier[]
     */
    protected $classifiers;

    /**
     * Get Classifiers
     *
     * @return Classifier[]
     */
    public function getClassifiers()
    {
        if (!isset($this->classifiers)) {
            $classifiers = array();

            foreach ($this->data->classifiers as $classifier) {
                $classifiers[] = new Classifier($classifier->classifier_id, $classifier->name);
            }

            $this->classifiers = $classifiers;
        }

        return $this->classifiers;
    }
}
