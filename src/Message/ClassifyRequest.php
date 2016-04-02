<?php

namespace Bobbyshaw\WatsonVisualRecognition\Message;

use GuzzleHttp\Exception\ClientException;
use Bobbyshaw\WatsonVisualRecognition\Exceptions\AuthException;
use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\Psr7\Request;

/**
 * Class ClassifyRequest
 * @package Bobbyshaw\WatsonVisualRecognition\Message
 */
class ClassifyRequest extends AbstractRequest
{
    const CLASSIFY_PATH = 'classify/';
    const ALLOWED_FILE_TYPES = ['.gif', '.jpg', '.png', '.zip'];

    /**
     * Get parameters for classify request
     *
     * @return array
     *
     * ['images_file', 'classifier_ids']
     *
     */
    public function getData()
    {
        $data = parent::getData();

        $data['images_file'] = $this->getImagesFile();

        if ($classifierIds = $this->getClassifierIds()) {
            $data['classifier_ids'] = $classifierIds;
        }

        return $data;
    }

    /**
     * Send Classify HTTP request
     *
     * @param array $data - array['images_file', 'classifier_ids']
     * @return ClassifyResponse
     * @throws AuthException
     * @throws \Exception
     */
    public function sendData($data)
    {
        if (!in_array(substr($data['images_file'], -4), static::ALLOWED_FILE_TYPES)) {
            throw new \InvalidArgumentException(
                'Image file needs to be one of the following types: ' . implode(', ', static::ALLOWED_FILE_TYPES)
            );
        }

        $params = [
            [
                'name' => 'images_file',
                'contents' => fopen($data['images_file'], 'r'),
                'filename' => $data['images_file']
            ]
        ];

        if (isset($data['classifier_ids'])) {
            if (is_array($data['classifier_ids'])) {
                $classifierIdParams = [];
                foreach ($data['classifier_ids'] as $id) {
                    $classifierIdParams[] = ['classifier_id' => $id];
                }

                $params[] = [
                    'name' => 'classifier_ids',
                    'contents' => json_encode(['classifiers' => $classifierIdParams])
                ];
            } else {
                throw new \InvalidArgumentException('Classifier IDs must be array');
            }
        }

        $multipartStream = new MultipartStream($params);

        $request = new Request(
            'POST',
            $this->getApiUrl(static::CLASSIFY_PATH) . '?' . http_build_query(['version' => $data['version']]),
            ['Authorization' => 'Basic ' . base64_encode($data['username'] . ':' . $data['password'])],
            $multipartStream
        );

        try {
            $response = $this->httpClient->send($request);

            if ($response->getStatusCode() != 200) {
                $error = $response->getStatusCode() . " Response Received: " . $response->getBody()->getContents();
                throw new \Exception($error, $response->getStatusCode());
            }
        } catch (ClientException $e) {
            if ($e->getCode() == 401) {
                throw new AuthException('Invalid credentials provided');
            } elseif ($e->getCode() == 415) {
                throw new \InvalidArgumentException('Unsupported image type');
            } else {
                throw $e;
            }
        }

        return $this->response = new ClassifyResponse($this, $response->getBody());
    }
}
