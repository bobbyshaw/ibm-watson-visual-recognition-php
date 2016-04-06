<?php

namespace Bobbyshaw\WatsonVisualRecognition\Tests;

use Bobbyshaw\WatsonVisualRecognition\Client;
use Bobbyshaw\WatsonVisualRecognition\Message\ClassifyRequest;
use Bobbyshaw\WatsonVisualRecognition\Message\GetClassifiersRequest;

/**
 * Class ClientTest
 * @package Bobbyshaw\WatsonVisualRecognition\Tests
 */
class ClientTest extends Base
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

        $this->client = new Client();
        $this->client->initialize($config);
    }

    public function testGetClassifiers()
    {
        $request = $this->client->getClassifiers();
        $this->assertInstanceOf(GetClassifiersRequest::class, $request);
    }

    public function testClassify()
    {
        $request = $this->client->classify();
        $this->assertInstanceOf(ClassifyRequest::class, $request);
    }

    public function testVersion()
    {
        $version = 'test';
        $this->client->setVersion($version);
        $this->assertEquals($version, $this->client->getVersion());
    }

    public function testUsername()
    {
        $username = 'test';
        $this->client->setUsername($username);
        $this->assertEquals($username, $this->client->getUsername());
    }

    public function testPassword()
    {
        $password = 'test';
        $this->client->setPassword($password);
        $this->assertEquals($password, $this->client->getPassword());
    }

    public function testInitialize()
    {
        $config = array(
            'username' => $this->username,
            'password' => $this->password,
        );

        $this->client = new Client();
        $this->client->initialize($config);

        $this->assertEquals($this->username, $this->client->getUsername());
        $this->assertEquals($this->password, $this->client->getPassword());
    }
}
