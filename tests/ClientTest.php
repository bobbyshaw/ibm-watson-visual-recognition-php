<?php

namespace Bobbyshaw\WatsonVisualRecognition\tests;

use Bobbyshaw\WatsonVisualRecognition\Client;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    /** @var Client */
    private $client;
    private $username = 'username';
    private $password = 'password';

    public function setUp()
    {
        $config = array(
            'username' => $this->username,
            'password' => $this->password,
        );

        $this->client = new Client($config);
    }

    /**
     * Test default endpoint URL
     */
    public function testApiDefaultVersionEndpoint()
    {
        $this->assertEquals(
            'https://gateway.watsonplatform.net/visual-recognition-beta/api/v2/',
            $this->client->getEndpoint()
        );
    }

    /**
     * Test endpoing URL with different version passed in
     *
     */
    public function testApiSpecifiedVersionEndpoint()
    {
        $config = array(
            'username' => $this->username,
            'password' => $this->password,
            'major_api_version' => 'v3'
        );

        $this->client = new Client($config);

        $this->assertEquals(
            'https://gateway.watsonplatform.net/visual-recognition-beta/api/v3/',
            $this->client->getEndpoint()
        );
    }

    /**
     * Test creating a request and constructing a URL of the correct format
     */
    public function testGetRequest()
    {
        $request = $this->client->getRequest('GET', 'classifiers/');

        $auth = $request->getHeader('Authorization');
        $this->assertEquals('Basic ' . base64_encode($this->username . ':' . $this->password), $auth[0]);
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('gateway.watsonplatform.net', $request->getUri()->getHost());
        $this->assertEquals('/visual-recognition-beta/api/v2/classifiers/', $request->getUri()->getPath());
        $this->assertEquals('version=' . date('Y-m-d'), $request->getUri()->getQuery());
    }

    /**
     * Test that the getClassifiers method returns an array of classifiers
     */
    public function testGetClassifiers()
    {
        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(200, [], '{ "classifiers":[
                {"classifier_id":"Black","name":"Black"},
                {"classifier_id":"Blue","name":"Blue"},
                {"classifier_id":"Brown","name":"Brown"},
                {"classifier_id":"Cyan","name":"Cyan"},
                {"classifier_id":"Green","name":"Green"},
                {"classifier_id":"Magenta","name":"Magenta"},
                {"classifier_id":"Mixed_Color","name":"Mixed_Color"},
                {"classifier_id":"Orange","name":"Orange"},
                {"classifier_id":"Red","name":"Red"},
                {"classifier_id":"Violet","name":"Violet"},
                {"classifier_id":"White","name":"White"},
                {"classifier_id":"Yellow","name":"Yellow"},
                {"classifier_id":"Black_and_white","name":"Black_and_white"},
                {"classifier_id":"Color","name":"Color"}]}')
        ]);

        $handler = HandlerStack::create($mock);
        $guzzle = new GuzzleClient(['handler' => $handler]);

        $config = array(
            'username' => $this->username,
            'password' => $this->password
        );

        $this->client = new Client($config, $guzzle);

        $classifiers = $this->client->getClassifiers();

        $this->assertCount(14, $classifiers);
        $this->assertEquals('Black', $classifiers[0]->getId());
        $this->assertEquals('Black', $classifiers[0]->getName());
    }
}
