<?php

namespace Bobbyshaw\WatsonVisualRecognition\Message;

use Bobbyshaw\WatsonVisualRecognition\Exceptions\AuthException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;

/**
 * Class GetClassifiersRequest
 * @package Bobbyshaw\WatsonVisualRecognition\Message
 */
class GetClassifiersRequest extends AbstractRequest
{
    const CLASSIFIERS_PATH = 'classifiers/';
    
    public function getData()
    {
        $data = parent::getData();

        if ($verbose = $this->getVerbose()) {
            $data['verbose'] = $verbose;
        }

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

        if (isset($data['verbose'])) {
            $query['verbose'] = $data['verbose'];
        }

        $request = new Request(
            'GET',
            $this->getApiUrl(static::CLASSIFIERS_PATH) . '?' . http_build_query($query),
            ['Authorization' => 'Basic ' . base64_encode($data['username'] . ':' . $data['password'])]
        );

        $this->response = null;

        try {
            $response = $this->httpClient->send($request);
            $this->response = new ClassifiersResponse($this, $response->getBody());
        } catch (ClientException $e) {
            if ($e->getCode() == 401) {
                throw new AuthException('Invalid credentials provided');
            }
        }

        return $this->response;
    }
}
