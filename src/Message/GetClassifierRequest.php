<?php

namespace Bobbyshaw\WatsonVisualRecognition\Message;

use Bobbyshaw\WatsonVisualRecognition\Exceptions\AuthException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;

/**
 * Class GetClassifierRequest
 * @package Bobbyshaw\WatsonVisualRecognition\Message
 */
class GetClassifierRequest extends AbstractRequest
{
    const CLASSIFIERS_PATH = 'classifiers/';

    public function getData()
    {
        $data = parent::getData();

        $data['classifier_id'] = $this->getClassifierId();

        return $data;
    }

    /**
     * Send HTTP request to get list of classifiers
     *
     * @param array $data
     * @return ClassifiersResponse
     * @throws AuthException
     */
    public function sendData($data)
    {
        $query = ['version' => $data['version']];

        $request = new Request(
            'GET',
            $this->getApiUrl(static::CLASSIFIERS_PATH) . $data['classifier_id'] . '?' . http_build_query($query),
            ['Authorization' => 'Basic ' . base64_encode($data['username'] . ':' . $data['password'])]
        );

        $this->response = null;

        try {
            $response = $this->httpClient->send($request);
            $this->response = new ClassifierResponse($this, $response->getBody());
        } catch (ClientException $e) {
            if ($e->getCode() == 401) {
                throw new AuthException('Invalid credentials provided');
            }
        }

        return $this->response;
    }
}
