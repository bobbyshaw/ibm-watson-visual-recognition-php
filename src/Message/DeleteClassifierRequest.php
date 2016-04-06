<?php

namespace Bobbyshaw\WatsonVisualRecognition\Message;

use Bobbyshaw\WatsonVisualRecognition\Exceptions\AuthException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\Psr7\Request;
use InvalidArgumentException;

/**
 * Class DeleteClassifierRequest
 * @package Bobbyshaw\WatsonVisualRecognition\Message
 */
class DeleteClassifierRequest extends AbstractRequest
{
    const CLASSIFIERS_PATH = 'classifiers/';


    public function getData()
    {
        $data = parent::getData();

        $data['classifier_id'] = $this->getClassifierId();

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
        $query = ['version' => $data['version']];

        $request = new Request(
            'DELETE',
            $this->getApiUrl(static::CLASSIFIERS_PATH) . $data['classifier_id'] . '?' . http_build_query($query),
            ['Authorization' => 'Basic ' . base64_encode($data['username'] . ':' . $data['password'])]
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

        return $this->response = new Response($this, $response->getBody());
    }
}
