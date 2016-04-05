<?php

namespace Bobbyshaw\WatsonVisualRecognition\Message;

use Bobbyshaw\WatsonVisualRecognition\Exceptions\AuthException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\Psr7\Request;
use InvalidArgumentException;

/**
 * Class CreateClassifierRequest
 * @package Bobbyshaw\WatsonVisualRecognition\Message
 */
class CreateClassifierRequest extends AbstractRequest
{
    const CLASSIFIERS_PATH = 'classifiers/';
    const ALLOWED_FILE_TYPES = ['.zip'];


    public function getData()
    {
        $data = parent::getData();

        $data['positive_examples'] = $this->getPositiveExamples();
        $data['negative_examples'] = $this->getNegativeExamples();
        $data['name'] = $this->getName();

        return $data;
    }

    /**
     * Send HTTP request to create a new classifier
     *
     * @param array $data
     * @return ClassifierResponse
     * @throws AuthException
     * @throws \Exception
     */
    public function sendData($data)
    {
        $this->validateData($data);

        $params = [
            [
                'name' => 'positive_examples',
                'contents' => fopen($data['positive_examples'], 'r'),
                'filename' => $data['positive_examples']
            ],
            [
                'name' => 'negative_examples',
                'contents' => fopen($data['negative_examples'], 'r'),
                'filename' => $data['negative_examples']
            ],
            [
                'name' => 'name',
                'contents' => $data['name']
            ]
        ];

        $multipartStream = new MultipartStream($params);

        $request = new Request(
            'POST',
            $this->getApiUrl(static::CLASSIFIERS_PATH) . '?' . http_build_query(['version' => $data['version']]),
            ['Authorization' => 'Basic ' . base64_encode($data['username'] . ':' . $data['password'])],
            $multipartStream
        );

        try {
            $response = $this->httpClient->send($request);

            if ($response->getStatusCode() != 200) {
                throw new \Exception($response->getBody()->getContents(), $response->getStatusCode());
            }
        } catch (ClientException $e) {
            if ($e->getCode() == 401) {
                throw new AuthException('Invalid credentials provided');
            }
        }

        return $this->response = new ClassifierResponse($this, $response->getBody());
    }


    /**
     * Validate Data
     *
     * @param $data
     * @return bool
     * @throws InvalidArgumentException
     */
    protected function validateData($data)
    {
        if (!in_array(substr($data['positive_examples'], -4), static::ALLOWED_FILE_TYPES)
            || !in_array(substr($data['negative_examples'], -4), static::ALLOWED_FILE_TYPES)) {
            throw new InvalidArgumentException(
                'Example files needs to be one of the following types: ' . implode(', ', static::ALLOWED_FILE_TYPES)
            );
        }

        return true;
    }
}
