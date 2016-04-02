<?php

namespace Bobbyshaw\WatsonVisualRecognition\Message;

use Symfony\Component\HttpFoundation\ParameterBag;
use GuzzleHttp\ClientInterface;
use Bobbyshaw\WatsonVisualRecognition\Helper;

/**
 * Class AbstractRequest
 *
 * This abstract class implements RequestInterface and defines a basic
 * set of functions that all Watson Requests are intended to include.
 *
 * Requests of this class are usually created using the createRequest
 * function and then actioned using methods within this
 * class or a class that extends this class.
 *
 * @package Bobbyshaw\WatsonVisualRecognition\Message
 */
abstract class AbstractRequest implements RequestInterface
{
    const ENDPOINT = 'https://gateway.watsonplatform.net/visual-recognition-beta/api/v2/';

    /**
     * The request parameters
     *
     * @var \Symfony\Component\HttpFoundation\ParameterBag
     */
    protected $parameters;

    /**
     * The request client.
     *
     * @var \GuzzleHttp\ClientInterface
     */
    protected $httpClient;


    /**
     * An associated ResponseInterface.
     *
     * @var ResponseInterface
     */
    protected $response;

    /**
     * Create a new Request
     *
     * @param ClientInterface $httpClient A Guzzle client to make API calls with
     */
    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
        $this->initialize();
    }

    /**
     * Get the watson API endpoint
     *
     * @api
     * @return string
     */
    public function getEndpoint()
    {
        return static::ENDPOINT;
    }

    /**
     * Get full API URL
     *
     * @param String $path of API needed
     * @returns String of full URL
     */
    public function getApiUrl($path)
    {
        return $this->getEndpoint() . $path;
    }

    /**
     * Initialize the object with parameters.
     *
     * @param array $parameters An associative array of parameters
     *
     * @return $this
     * @throws \RuntimeException
     */
    public function initialize(array $parameters = array())
    {
        if (null !== $this->response) {
            throw new \RuntimeException('Request cannot be modified after it has been sent!');
        }

        $this->parameters = new ParameterBag;

        Helper::initialize($this, $parameters);

        return $this;
    }

    /**
     * Get all parameters as an associative array.
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters->all();
    }

    /**
     * Get a single parameter.
     *
     * @param string $key The parameter key
     * @return mixed
     */
    protected function getParameter($key)
    {
        return $this->parameters->get($key);
    }

    /**
     * Set a single parameter
     *
     * @param string $key The parameter key
     * @param mixed $value The value to set
     * @return AbstractRequest Provides a fluent interface
     * @throws \RuntimeException if a request parameter is modified after the request has been sent.
     */
    protected function setParameter($key, $value)
    {
        if (null !== $this->response) {
            throw new \RuntimeException('Request cannot be modified after it has been sent!');
        }

        $this->parameters->set($key, $value);

        return $this;
    }

    /**
     * Get Username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->getParameter('username');
    }

    /**
     * Set Username
     *
     * @param string $value
     * @return $this
     */
    public function setUsername($value)
    {
        $this->setParameter('username', $value);

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->getParameter('password');
    }

    /**
     * Set Password
     *
     * @param string $value
     * @return $this
     */
    public function setPassword($value)
    {
        $this->setParameter('password', $value);

        return $this;
    }

    /**
     * Get Version
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->getParameter('version');
    }

    /**
     * Set verbose parameter
     *
     * @return string
     */
    public function getVerbose()
    {
        return $this->getParameter('verbose');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setVerbose($value)
    {
        $this->setParameter('verbose', $value);

        return $this;
    }

    /**
     * Get image file.
     *
     * @return string
     */
    public function getImagesFile()
    {
        return $this->getParameter('images_file');
    }

    /**
     * Set Image file
     *
     * @param string $value
     * @return $this
     */
    public function setImagesFile($value)
    {
        $this->setParameter('images_file', $value);

        return $this;
    }

    /**
     * Get Classifier Ids
     *
     * @return string[]
     */
    public function getClassifierIds()
    {
        return $this->getParameter('classifier_ids');
    }

    /**
     * Set Classifier Ids
     *
     * @param string[] $value
     * @return $this
     */
    public function setClassifierIds($value)
    {
        $this->setParameter('classifier_ids', $value);

        return $this;
    }

    /**
     * Get Classifier ID
     *
     * @return String
     */
    public function getClassifierId()
    {
        return $this->getParameter('classifier_id');
    }

    /**
     * Set Classifier ID
     *
     * @param String $value
     * @return $this
     */
    public function setClassifierId($value)
    {
        $this->setParameter('classifier_id', $value);

        return $this;
    }

    /**
     * Set version
     *
     * @param $value
     * @return $this
     */
    public function setVersion($value)
    {
        $this->setParameter('version', $value);

        return $this;
    }


    /**
     * Configure command is run before every request is sent
     */
    public function configure()
    {
    }

    /**
     * Get Default request data params
     *
     * @return array
     */
    public function getData()
    {
        return [
            'username' => $this->getUsername(),
            'password' => $this->getPassword(),
            'version' => $this->getVersion()
        ];
    }


    /**
     * Send the request
     *
     * @return ResponseInterface
     */
    public function send()
    {
        $this->configure();

        $data = $this->getData();

        return $this->sendData($data);
    }

    /**
     * Get the associated Response.
     *
     * @return ResponseInterface
     */
    public function getResponse()
    {
        if (null === $this->response) {
            throw new \RuntimeException('You must call send() before accessing the Response!');
        }

        return $this->response;
    }
}
