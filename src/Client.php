<?php

namespace Bobbyshaw\WatsonVisualRecognition;

use GuzzleHttp;
use Bobbyshaw\WatsonVisualRecognition\Exceptions\AuthException;

/**
 * This is the primary library class which handles request to the IBM Watson Visual Recognition Service
 *
 * @package Bobbyshaw\WatsonVisualRecognition
 * @author Tom Robertshaw <me@tomrobertshaw.net>
 * @api
 */
class Client
{
    const ENDPOINT = 'https://gateway.watsonplatform.net/visual-recognition-beta/api/';
    const CLASSIFIERS_PATH = 'classifiers/';
    const CLASSIFY_PATH = 'classify/';
    const ALLOWED_FILE_TYPES = ['.gif', '.jpg', '.png', '.zip'];

    /**
     * IBM Watson Service Username
     * @var String
     */
    private $username;

    /**
     * IBM Watson Service Password
     *
     * @var String
     */
    private $password;

    /**
     * Major API Version
     *
     * @var string
     */
    private $majorApiVersion;

    /**
     * API version Date
     *
     * @var string
     */
    private $version;

    /**
     * Guzzle HTTP Client for making requests
     *
     * @var GuzzleHttp\Client
     */
    private $client;

    /**
     * Instantiate Visual Recognition client with API credentials
     *
     * @api
     * @param array $config
     *  - username - service credentials
     *  - password - service credentials
     *  - version_date - Date of API release that you wish to request from. Defaults to current date to get
     *  latest version
     *  - major_api_version - Major api release, Defaults to v2.
     *
     * @param array $config
     * @param GuzzleHttp\Client|null $client
     * @throws \Exception
     */
    public function __construct(array $config = [], GuzzleHttp\Client $client = null)
    {
        $this->username = $config['username'];
        $this->password = $config['password'];
        $this->version = isset($config['version']) ? $config['version'] : date('Y-m-d');
        $this->majorApiVersion = isset($config['major_api_version']) ? $config['major_api_version'] : 'v2';
        $this->client = $client ?: new GuzzleHttp\Client();

        if (!$this->username) {
            throw new \Exception('username not set');
        }

        if (!$this->password) {
            throw new \Exception('password not set');
        }
    }

    /**
     *
     * Get list of available classifiers
     *
     * @api
     * @return mixed
     * @throws \Exception
     */
    public function getClassifiers()
    {
        try {
            $response = $this->client->get($this->getApiUrl(static::CLASSIFIERS_PATH), [
                'auth' => [$this->username, $this->password],
                'query' => [
                    'version' => $this->version,
                ]
            ]);

            if ($response->getStatusCode() != 200) {
                throw new \Exception($response->getBody()->getContents(), $response->getStatusCode());
            }

            $result = json_decode($response->getBody()->getContents());
        } catch (GuzzleHttp\Exception\ClientException $e) {
            if ($e->getCode() == 401) {
                throw new AuthException('Invalid credentials provided');
            } else {
                throw $e;
            }
        }

        $classifiers = [];

        if (!$result->classifiers) {
            throw new \Exception('No classifiers found');
        }

        foreach ($result->classifiers as $item) {
            $classifiers[] = new Classifier($item->classifier_id, $item->name);
        }

        return $classifiers;
    }

    /**
     * Classify an image or zip of images.
     *
     * @param $image - The image file path (.jpg, .png, .jpg) or compressed (.zip) file of images to classify.
     * @param array|null $classifierIds - Array of classifiers IDs to restrict classification to.
     * @return Image[] which also contains Classifier[]
     * @throws \InvalidArgumentException if image is incorrect file format or if classifier IDs is not an array
     * @throws \Exception if error is returned from
     */
    public function classify($image, $classifierIds = null)
    {
        if (!in_array(substr($image, -4), static::ALLOWED_FILE_TYPES)) {
            throw new \InvalidArgumentException(
                'Image file needs to be one of the following types: ' . implode(', ', static::ALLOWED_FILE_TYPES)
            );
        }

        $params = [
            [
                'name' => 'images_file',
                'contents' => fopen($image, 'r'),
                'filename' => $image
            ]
        ];

        if (!is_null($classifierIds)) {
            if (is_array($classifierIds)) {
                $classifierIdParams = [];
                foreach ($classifierIds as $id) {
                    $classifierIdParams[] = ['classifier_id' => $id];
                }

                $params[] = [
                    'name' => 'classifier_ids',
                    'contents' => json_encode(['classifiers' => $classifierIds])
                ];
            } else {
                throw new \InvalidArgumentException('Classifier IDs must be array');
            }
        }

        try {
            $response = $this->client->post($this->getApiUrl(static::CLASSIFY_PATH), [
                'auth' => [$this->username, $this->password],
                'multipart' => $params,
                'query' => [
                    'version' => $this->version,
                ]
            ]);

            if ($response->getStatusCode() != 200) {
                throw new \Exception($response->getBody()->getContents(), $response->getStatusCode());
            }

            $results = json_decode($response->getBody()->getContents());
        } catch (GuzzleHttp\Exception\ClientException $e) {
            if ($e->getCode() == 401) {
                throw new AuthException('Invalid credentials provided');
            } elseif ($e->getCode() == 415) {
                throw new \InvalidArgumentException('Unsupported image type');
            } else {
                throw $e;
            }
        }

        $images = [];

        foreach ($results->images as $image) {
            $classifiers = [];
            foreach ($image->scores as $item) {
                $classifiers[] = new Classifier($item->classifier_id, $item->name, $item->score);
            }

            $images[] = new Image($image->image, $classifiers);
        }

        return $images;
    }

    /**
     * Get the watson API endpoint with version path
     *
     * @api
     * @return string
     */
    public function getEndpoint()
    {
        return static::ENDPOINT . $this->majorApiVersion . '/';
    }

    /**
     * Get full API URL f
     *
     * @param String $path of API needed
     * @returns String of full URL
     */
    public function getApiUrl($path)
    {
        return $this->getEndpoint() . $path;
    }
}
