<?php

namespace Bobbyshaw\WatsonVisualRecognition\Message;

/**
 * Response
 */
class Response extends AbstractResponse
{

    /**
     * Constructor
     *
     * @param  RequestInterface $request
     * @param  string $data / response data
     */
    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);

        $this->request = $request;
        $this->data = json_decode($data);
    }


    /**
     * Whether or not response is successful
     *
     * @return bool
     */
    public function isSuccessful()
    {
        return !isset($this->data->error);
    }


    /**
     * Get error message
     *
     * @return string
     */
    public function getErrorMessage()
    {
        if (!$this->isSuccessful()) {
            return $this->data->error;
        }

        return '';
    }

    /**
     * @return bool|int
     */
    public function getErrorCode()
    {
        if (!$this->isSuccessful()) {
            return $this->data->code;
        }

        return false;
    }
}
