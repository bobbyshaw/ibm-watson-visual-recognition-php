<?php

namespace Bobbyshaw\WatsonVisualRecognition\Message;

use Bobbyshaw\WatsonVisualRecognition\Classifier;
use Bobbyshaw\WatsonVisualRecognition\Image;

/**
 * Class ClassifyResponse
 * @package Bobbyshaw\WatsonVisualRecognition\Message
 */
class ClassifyResponse extends Response
{
    /**
     * Images in the response
     *
     * @var Image[]
     */
    protected $images;

    /**
     * Get Images
     *
     * @return Image[]
     */
    public function getImages()
    {
        if (empty($this->images)) {
            $images = [];
            foreach ($this->data->images as $image) {
                $classifiers = [];
                if (isset($image->scores)) {
                    foreach ($image->scores as $classifier) {
                        $classifiers[] = new Classifier(
                            $classifier->classifier_id,
                            $classifier->name,
                            $classifier->score
                        );
                    }
                }
                $images[] = new Image($image->image, $classifiers);
            }
            $this->images = $images;
        }

        return $this->images;
    }
}
