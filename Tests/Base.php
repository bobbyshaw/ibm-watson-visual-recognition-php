<?php

namespace Bobbyshaw\WatsonVisualRecognition\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit_Framework_TestCase;
use ReflectionObject;

/**
 * Class Base
 * @package Bobbyshaw\WatsonVisualRecognition\Tests
 */
abstract class Base extends PHPUnit_Framework_TestCase
{
    /**
     * @var ClientInterface
     */
    protected $httpClient;

    /**
     * Get a mock response for a client by mock file name
     *
     * @param string $path Relative path to the mock response file
     * @param string $code HTTP response code to respond with
     *
     * @return Response
     */
    public function getMockHttpResponse($path, $code = 200)
    {
        $ref = new ReflectionObject($this);
        $dir = dirname($ref->getFileName());

        // if mock file doesn't exist, check parent directory
        if (!file_exists($dir . '/Mock/' . $path) && file_exists($dir . '/../Mock/' . $path)) {
            return new Response($code, [], file_get_contents($dir . '/../Mock/' . $path));
        }

        return new Response($code, [], file_get_contents($dir . '/Mock/' . $path));
    }

    /**
     * Get Guzzle with mock response and request history stored in container
     *
     * @param [] $container
     * @param Response[] $responses
     * @return Client
     */
    public function getMockHttpClientWithHistoryAndResponses(&$container, $responses)
    {
        $mock = new MockHandler($responses);

        $stack = HandlerStack::create($mock);
        $history = Middleware::history($container);
        $stack->push($history);
        
        return new Client(['handler' => $stack]);
    }

    /**
     * Get HTTP Client or default one if none set
     *
     * @return ClientInterface Client
     */
    public function getHttpClient()
    {
        if (null === $this->httpClient) {
            $this->httpClient = new Client();
        }

        return $this->httpClient;
    }
}
