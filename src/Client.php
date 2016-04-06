<?php

namespace Bobbyshaw\WatsonVisualRecognition;

use Bobbyshaw\WatsonVisualRecognition\Message\ClassifyRequest;
use Bobbyshaw\WatsonVisualRecognition\Message\CreateClassifierRequest;
use Bobbyshaw\WatsonVisualRecognition\Message\DeleteClassifierRequest;
use Bobbyshaw\WatsonVisualRecognition\Message\GetClassifierRequest;
use Bobbyshaw\WatsonVisualRecognition\Message\GetClassifiersRequest;
use Bobbyshaw\WatsonVisualRecognition\Message\RequestInterface;
use GuzzleHttp\ClientInterface as HttpClientInterface;
use GuzzleHttp\Client as HttpClient;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * This is the primary library class which handles request to the IBM Watson Visual Recognition Service
 *
 * @package Bobbyshaw\WatsonVisualRecognition
 * @author Tom Robertshaw <me@tomrobertshaw.net>
 * @api
 */
class Client implements ClientInterface
{

    /**
     * Request parameters
     *
     * @var \Symfony\Component\HttpFoundation\ParameterBag
     */
    protected $parameters;

    /**
     * HTTP Client
     * @var \GuzzleHttp\ClientInterface
     */
    protected $httpClient;

    /**
     * Instantiate Visual Recognition client with API credentials
     *
     * @api
     * @param HttpClientInterface|null $httpClient
     * @throws \Exception
     */
    public function __construct(HttpClientInterface $httpClient = null)
    {
        $this->httpClient = $httpClient ?: $this->getDefaultHttpClient();
    }

    /**
     * Get the global default HTTP client.
     *
     * @return HttpClient
     */
    protected function getDefaultHttpClient()
    {
        return new HttpClient(
            array(
                'curl.options' => array(CURLOPT_CONNECTTIMEOUT => 60),
            )
        );
    }

    /**
     * Initialize this client with default parameters
     *
     * @param  array $parameters
     * @return $this
     */
    public function initialize(array $parameters = array())
    {
        $this->parameters = new ParameterBag;

        // set default parameters
        foreach ($this->getDefaultParameters() as $key => $value) {
            $this->parameters->set($key, $value);
        }

        Helper::initialize($this, $parameters);

        return $this;
    }

    /**
     * Create new request object and initialize.
     *
     * @param $class
     * @param array $parameters
     * @return mixed
     */
    protected function createRequest($class, array $parameters)
    {
        /** @var RequestInterface $obj */
        $obj = new $class($this->httpClient);

        return $obj->initialize(array_replace($this->getParameters(), $parameters));
    }

    /**
     * Default & required parameters for requests
     *
     */
    public function getDefaultParameters()
    {
        return array(
            'username' => '',
            'password' => '',
            'version' => '2015-12-02'
        );
    }

    /**
     * Get Parameters
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters->all();
    }

    /**
     * Get Username
     *
     * @return mixed
     */
    public function getUsername()
    {
        return $this->parameters->get('username');
    }

    /**
     * Set Username
     *
     * @param $value
     * @return $this
     */
    public function setUsername($value)
    {
        $this->parameters->set('username', $value);

        return $this;
    }

    /**
     * Get password
     *
     * @return mixed
     */
    public function getPassword()
    {
        return $this->parameters->get('password');
    }

    /**
     * Set Password
     *
     * @param $value
     * @return $this
     */
    public function setPassword($value)
    {
        $this->parameters->set('password', $value);

        return $this;
    }

    /**
     * Get Version
     *
     * @return mixed
     */
    public function getVersion()
    {
        return $this->parameters->get('version');
    }

    /**
     * Set version
     *
     * @param $value
     * @return $this
     */
    public function setVersion($value)
    {
        $this->parameters->set('version', $value);

        return $this;
    }

    /**
     * Get list of available classifiers
     *
     * @api
     * @param array $parameters
     * @return GetClassifiersRequest
     * @throws \Exception
     */
    public function getClassifiers(array $parameters = [])
    {
        return $this->createRequest(GetClassifiersRequest::class, $parameters);
    }

    /**
     * Get detail on individual classifier
     *
     * @param array $parameters
     * @return mixed
     */
    public function getClassifier(array $parameters = [])
    {
        return $this->createRequest(GetClassifierRequest::class, $parameters);
    }

    /**
     * Classify image
     *
     * @param array $parameters
     * @return ClassifyRequest
     */
    public function classify(array $parameters = [])
    {
        return $this->createRequest(ClassifyRequest::class, $parameters);
    }

    /**
     * Train a new classifier
     *
     * @param array $parameters
     * @return mixed
     */
    public function createClassifier(array $parameters = [])
    {
        return $this->createRequest(CreateClassifierRequest::class, $parameters);
    }

    /**
     * Delete a classifier
     *
     * @param array $parameters
     * @return mixed
     */
    public function deleteClassifier(array $parameters = [])
    {
        return $this->createRequest(DeleteClassifierRequest::class, $parameters);
    }
}
