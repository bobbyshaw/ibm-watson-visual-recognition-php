<?php

namespace Bobbyshaw\WatsonVisualRecognition\Tests;

use Bobbyshaw\WatsonVisualRecognition\Client;
use Bobbyshaw\WatsonVisualRecognition\Image;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;

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
     * Test the getClassifiers function HTTP request and response
     *
     * @throws \Bobbyshaw\WatsonVisualRecognition\Exceptions\AuthException
     * @throws \Exception
     */
    public function testGetClassifiers()
    {
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
                {"classifier_id":"Color","name":"Color"}]}'),
        ]);

        $stack = HandlerStack::create($mock);
        $container = [];
        $history = Middleware::history($container);
        $stack->push($history);
        $guzzle = new GuzzleClient(['handler' => $stack]);

        $config = array(
            'username' => $this->username,
            'password' => $this->password
        );

        $this->client = new Client($config, $guzzle);
        $classifiers = $this->client->getClassifiers();

        // One request should be sent
        $this->assertCount(1, $container);

        $transaction = $container[0];

        /** @var Request $request */
        $request = $transaction['request'];

        /** @var Uri $uri */
        $uri = $request->getUri();

        // Check method
        $this->assertEquals('GET', $request->getMethod());

        // Check we're talking to the right host
        $this->assertEquals('https', $uri->getScheme());
        $this->assertEquals('gateway.watsonplatform.net', $uri->getHost());

        // Check version query parameter
        $query = \GuzzleHttp\Psr7\parse_query($uri->getQuery());
        $this->assertArrayHasKey('version', $query);

        // Check path
        $this->assertEquals('/visual-recognition-beta/api/v2/classifiers/', $uri->getPath());

        // Check basic auth
        $auth = $request->getHeader('Authorization');
        $this->assertEquals('Basic ' . base64_encode($this->username . ':' . $this->password), $auth[0]);

        // Check response
        $this->assertCount(14, $classifiers);
        $this->assertEquals('Black', $classifiers[0]->getId());
        $this->assertEquals('Black', $classifiers[0]->getName());
    }

    /**
     * Test getClassifiers failed auth is handled appropriately
     *
     * @expectedException Bobbyshaw\WatsonVisualRecognition\Exceptions\AuthException
     */
    public function testGetClassifiersFailedAuthResponse()
    {
        // Create a mock and queue two responses.
        $html = <<<'EOD'
<HTML>
    <HEAD>
        <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
        <TITLE>Watson Error</TITLE>
    </HEAD>
    <BODY>
        <HR>
        <p>Invalid access to resource - /visual-recognition-beta/api/v2/classify?version=2015-12-28</p>
        <p>User access not Authorized.</p><p>Gateway Error Code : ERCD250-LDAP-DN-AUTHERR</p>
        <p>Unable to communicate with Watson.</p>
        <p>Request URL : /visual-recognition-beta/api/v2/classify?version=2015-12-28</p>
        <p>Error Id :  csf_platform_prod_dp02-18916189</p><p>Date-Time : 2016-01-03T09:04:54-05:00</p>
    </BODY>
</HTML>
EOD;

        $mock = new MockHandler([
            new Response(401, [], $html)
        ]);

        $handler = HandlerStack::create($mock);
        $guzzle = new GuzzleClient(['handler' => $handler]);

        $config = array(
            'username' => $this->username,
            'password' => $this->password
        );

        $this->client = new Client($config, $guzzle);

        $this->client->getClassifiers();
    }

    /**
     * Test classify function HTTP request sent and success response handle
     *
     * @throws \Bobbyshaw\WatsonVisualRecognition\Exceptions\AuthException
     * @throws \Exception
     */
    public function testClassify()
    {
        $mock = new MockHandler([
            new Response(200, [], '{"images":[
                {"image":"cosmos-flower-433424_640.jpg",
                 "scores":[
                     {"classifier_id":"Flower","name":"Flower","score":0.71803},
                     {"classifier_id":"Arthropod","name":"Arthropod","score":0.694351},
                     {"classifier_id":"Sky_Scene","name":"Sky_Scene","score":0.668133},
                     {"classifier_id":"Invertebrate","name":"Invertebrate","score":0.667683},
                     {"classifier_id":"Butterfly","name":"Butterfly","score":0.651707},
                     {"classifier_id":"Fireworks","name":"Fireworks","score":0.641492},
                     {"classifier_id":"Fireworks","name":"Fireworks","score":0.641492},
                     {"classifier_id":"Flower_Scene","name":"Flower_Scene","score":0.625469},
                     {"classifier_id":"Graduation","name":"Graduation","score":0.620176},
                     {"classifier_id":"Burning","name":"Burning","score":0.613739},
                     {"classifier_id":"Concert","name":"Concert","score":0.585348},
                     {"classifier_id":"Escalator","name":"Escalator","score":0.544081},
                     {"classifier_id":"Night_Sky","name":"Night_Sky","score":0.509917},
                     {"classifier_id":"Ballroom_or_Disco","name":"Ballroom_or_Disco","score":0.503031}]}]
            }')
        ]);

        $container = [];
        $history = Middleware::history($container);

        $stack = HandlerStack::create($mock);
        // Add the history middleware to the handler stack.
        $stack->push($history);

        $guzzle = new GuzzleClient(['handler' => $stack]);

        $config = array(
            'username' => $this->username,
            'password' => $this->password
        );

        $this->client = new Client($config, $guzzle);
        $images = $this->client->classify(__DIR__ . '/images/cosmos-flower-433424_640.jpg');

        // One request sent
        $this->assertCount(1, $container);

        $transaction = $container[0];

        /** @var Request $request */
        $request = $transaction['request'];

        /** @var Uri $uri */
        $uri = $request->getUri();

        // Check method
        $this->assertEquals('POST', $request->getMethod());

        // Check we're talking to the right host
        $this->assertEquals('https', $uri->getScheme());
        $this->assertEquals('gateway.watsonplatform.net', $uri->getHost());

        // Check version query parameter
        $query = \GuzzleHttp\Psr7\parse_query($uri->getQuery());
        $this->assertArrayHasKey('version', $query);

        // Check path
        $this->assertEquals('/visual-recognition-beta/api/v2/classify/', $uri->getPath());

        // Check basic auth
        $auth = $request->getHeader('Authorization');
        $this->assertEquals('Basic ' . base64_encode($this->username . ':' . $this->password), $auth[0]);

        // Check response
        $classifiers = $images[0]->getClassifiers();
        $this->assertCount(14, $classifiers);

        $this->assertEquals('Flower', $classifiers[0]->getId());
        $this->assertEquals('Flower', $classifiers[0]->getName());
        $this->assertEquals(0.71803, $classifiers[0]->getScore());
    }

    /**
     * Test that the classify function returns an array of classifiers and scores for classifiers specified
     */
    public function testClassifyWithSpecificClassifiersResponse()
    {
        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(200, [], '{"images":[
                {"image":"cosmos-flower-433424_640.jpg",
                 "scores":[
                     {"classifier_id":"Flower","name":"Flower","score":0.71803},
                     {"classifier_id":"Concert","name":"Concert","score":0.585348}]}]
            }')
        ]);

        $handler = HandlerStack::create($mock);
        $guzzle = new GuzzleClient(['handler' => $handler]);

        $config = array(
            'username' => $this->username,
            'password' => $this->password
        );

        $this->client = new Client($config, $guzzle);

        $images = $this->client->classify(__DIR__ . '/images/cosmos-flower-433424_640.jpg', ['Flower', 'Concert']);
        $this->assertCount(1, $images);

        /** @var Image $image */
        $image = $images[0];

        $classifiers = $image->getClassifiers();
        $this->assertCount(2, $classifiers);

        $this->assertEquals('Flower', $classifiers[0]->getId());
        $this->assertEquals('Concert', $classifiers[1]->getId());
    }

    /**
     * Test that the classify function returns an array of images when presented with a zip
     */
    public function testClassifyWithZipResponse()
    {
        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(200, [], '{"images":[
                {"image":"cosmos-flower-433424_640.jpg",
                 "scores":[
                     {"classifier_id":"Flower","name":"Flower","score":0.71803},
                     {"classifier_id":"Arthropod","name":"Arthropod","score":0.694351},
                     {"classifier_id":"Sky_Scene","name":"Sky_Scene","score":0.668133},
                     {"classifier_id":"Invertebrate","name":"Invertebrate","score":0.667683},
                     {"classifier_id":"Butterfly","name":"Butterfly","score":0.651707},
                     {"classifier_id":"Fireworks","name":"Fireworks","score":0.641492},
                     {"classifier_id":"Fireworks","name":"Fireworks","score":0.641492},
                     {"classifier_id":"Flower_Scene","name":"Flower_Scene","score":0.625469},
                     {"classifier_id":"Graduation","name":"Graduation","score":0.620176},
                     {"classifier_id":"Burning","name":"Burning","score":0.613739},
                     {"classifier_id":"Concert","name":"Concert","score":0.585348},
                     {"classifier_id":"Escalator","name":"Escalator","score":0.544081},
                     {"classifier_id":"Night_Sky","name":"Night_Sky","score":0.509917},
                     {"classifier_id":"Ballroom_or_Disco","name":"Ballroom_or_Disco","score":0.503031}]},

                {"image":"hummingbird-1047836_640.jpg",
                 "scores":[
                     {"classifier_id":"Snow_Scene","name":"Snow_Scene","score":0.657084},
                     {"classifier_id":"Cormorant","name":"Cormorant","score":0.580621},
                     {"classifier_id":"Sky_Scene","name":"Sky_Scene","score":0.578027},
                     {"classifier_id":"Bird","name":"Bird","score":0.575492},
                     {"classifier_id":"Cyan","name":"Cyan","score":0.566259},
                     {"classifier_id":"Outdoors","name":"Outdoors","score":0.564004},
                     {"classifier_id":"Nature_Scene","name":"Nature_Scene","score":0.552658},
                     {"classifier_id":"Winter_Scene","name":"Winter_Scene","score":0.550338},
                     {"classifier_id":"Helicopter","name":"Helicopter","score":0.533052},
                     {"classifier_id":"Slalom","name":"Slalom","score":0.531014},
                     {"classifier_id":"Figure_Skating","name":"Figure_Skating","score":0.529651},
                     {"classifier_id":"Animal","name":"Animal","score":0.528001}]}]
            }')
        ]);

        $handler = HandlerStack::create($mock);
        $guzzle = new GuzzleClient(['handler' => $handler]);

        $config = array(
            'username' => $this->username,
            'password' => $this->password
        );

        $this->client = new Client($config, $guzzle);

        $images = $this->client->classify(__DIR__ . '/images/Cosmos-Flower-And-Hummingbird.zip');
        $this->assertCount(2, $images);

        $this->assertEquals('cosmos-flower-433424_640.jpg', $images[0]->getName());
        $classifiers = $images[0]->getClassifiers();
        $this->assertCount(14, $classifiers);

        $this->assertEquals('Flower', $classifiers[0]->getId());
        $this->assertEquals('Arthropod', $classifiers[1]->getId());

        $this->assertEquals('hummingbird-1047836_640.jpg', $images[1]->getName());
        $classifiers = $images[1]->getClassifiers();
        $this->assertCount(12, $classifiers);

        $this->assertEquals('Snow_Scene', $classifiers[0]->getId());
        $this->assertEquals('Cormorant', $classifiers[1]->getId());
    }

    /**
     * Test classify failed auth is handled appropriately
     *
     * @expectedException Bobbyshaw\WatsonVisualRecognition\Exceptions\AuthException
     */
    public function testClassifyAuthExceptionResponse()
    {
        // Create a mock and queue two responses.
        $html = <<<'EOD'
<HTML>
    <HEAD>
        <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
        <TITLE>Watson Error</TITLE>
    </HEAD>
    <BODY>
        <HR>
        <p>Invalid access to resource - /visual-recognition-beta/api/v2/classify?version=2015-12-28</p>
        <p>User access not Authorized.</p><p>Gateway Error Code : ERCD250-LDAP-DN-AUTHERR</p>
        <p>Unable to communicate with Watson.</p>
        <p>Request URL : /visual-recognition-beta/api/v2/classify?version=2015-12-28</p>
        <p>Error Id :  csf_platform_prod_dp02-18916189</p><p>Date-Time : 2016-01-03T09:04:54-05:00</p>
    </BODY>
</HTML>
EOD;
        $mock = new MockHandler([
            new Response(401, [], $html)
        ]);

        $handler = HandlerStack::create($mock);
        $guzzle = new GuzzleClient(['handler' => $handler]);

        $config = array(
            'username' => $this->username,
            'password' => $this->password
        );

        $this->client = new Client($config, $guzzle);

        $this->client->classify(__DIR__ . '/images/cosmos-flower-433424_640.jpg');
    }

    /**
     * Test classify failed auth is handled appropriately
     *
     * @expectedException \InvalidArgumentException
     */
    public function testClassifyInvalidArgumentExceptionResponse()
    {
        // Create a mock and queue two responses.
        $html = <<<'EOD'
<HTML>
    <HEAD>
        <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
        <TITLE>Watson Error</TITLE>
    </HEAD>
    <BODY>
        <HR>
        <p>Invalid access to resource - /visual-recognition-beta/api/v2/classify?version=2015-12-28</p>
        <p>User access not Authorized.</p><p>Gateway Error Code : ERCD250-LDAP-DN-AUTHERR</p>
        <p>Unable to communicate with Watson.</p>
        <p>Request URL : /visual-recognition-beta/api/v2/classify?version=2015-12-28</p>
        <p>Error Id :  csf_platform_prod_dp02-18916189</p><p>Date-Time : 2016-01-03T09:04:54-05:00</p>
    </BODY>
</HTML>
EOD;
        $mock = new MockHandler([
            new Response(401, [], $html)
        ]);

        $handler = HandlerStack::create($mock);
        $guzzle = new GuzzleClient(['handler' => $handler]);

        $config = array(
            'username' => $this->username,
            'password' => $this->password
        );

        $this->client = new Client($config, $guzzle);

        $this->client->classify(__DIR__ . '/images/cosmos-flower-433424_640.doc');
    }
}
