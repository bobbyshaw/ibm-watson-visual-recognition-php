<?php

namespace Bobbyshaw\WatsonVisualRecognition\Message;

use Bobbyshaw\WatsonVisualRecognition\Classifier;
use DateTime;

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
                $owner = isset($classifier->owner) ? $classifier->owner : null;
                $created = isset($classifier->created) ? new DateTime($classifier->created): null;

                $classifiers[] = new Classifier($classifier->classifier_id, $classifier->name, null, $owner, $created);
            }

            $this->classifiers = $classifiers;
        }

        return $this->classifiers;
    }
}
