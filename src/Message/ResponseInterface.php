<?php

namespace Bobbyshaw\WatsonVisualRecognition\Message;

/**
 * Response Interface
 * @package Bobbyshaw\WatsonVisualRecognition\Message
 */
interface ResponseInterface extends MessageInterface
{
    /**
     * Get the original request which generated this response
     *
     * @return RequestInterface
     */
    public function getRequest();
}
