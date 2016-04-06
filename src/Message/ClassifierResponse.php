<?php

namespace Bobbyshaw\WatsonVisualRecognition\Message;

use Bobbyshaw\WatsonVisualRecognition\Classifier;
use DateTime;

/**
 * Class ClassifierResponse
 * @package Bobbyshaw\WatsonVisualRecognition\Message
 */
class ClassifierResponse extends Response
{
    /**
     * @var Classifier
     */
    protected $classifier;

    /**
     * Get Classifiers
     *
     * @return Classifier
     */
    public function getClassifier()
    {
        if (!isset($this->classifier)) {
            if (isset($this->data->classifier_id) && isset($this->data->name) && isset($this->data->owner)
                && isset($this->data->created)
            ) {
                $this->classifier = new Classifier(
                    $this->data->classifier_id,
                    $this->data->name,
                    null,
                    $this->data->owner,
                    new DateTime($this->data->created)
                );
            }
        }

        return $this->classifier;
    }
}
