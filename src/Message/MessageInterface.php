<?php

namespace Bobbyshaw\WatsonVisualRecognition\Message;

interface MessageInterface
{
    /**
     * Get data for message
     *
     * @return array
     */
    public function getData();
}
