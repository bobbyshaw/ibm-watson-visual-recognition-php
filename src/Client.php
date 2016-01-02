<?php

namespace Bobbyshaw\WatsonVisualRecognition;

use GuzzleHttp;

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
     * @param null $params
     * @return mixed
     * @throws \Exception
     */
    public function getClassifiers($params = null)
    {
        $request = $this->getRequest('GET', static::CLASSIFIERS_PATH, $params);
        $response = $this->client->send($request);

        if ($response->getStatusCode() != 200) {
            throw new \Exception($response->getBody()->getContents(), $response->getStatusCode());
        }

        $result = json_decode($response->getBody()->getContents());

        $classifiers = array();

        if (!$result->classifiers) {
            throw new \Exception('No classifiers found');
        }

        foreach ($result->classifiers as $item) {
            $classifiers[] = new Classifier($item->classifier_id, $item->name);
        }

        return $classifiers;
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
     * Build the Request
     *
     * @param $method
     * @param $path
     * @param array $params
     * @return GuzzleHttp\Psr7\Request
     */
    public function getRequest($method, $path, $params = array())
    {
        $headers = array(
            'Authorization' => 'Basic ' . base64_encode($this->username . ':' . $this->password)
        );

        $params['version'] = $this->version;

        $url = $this->getEndpoint() . $path . "?" . GuzzleHttp\Psr7\build_query($params);

        $request = new GuzzleHttp\Psr7\Request($method, $url, $headers);

        return $request;
    }
}
