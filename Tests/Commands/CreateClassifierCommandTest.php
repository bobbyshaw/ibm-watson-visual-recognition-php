<?php

namespace Bobbyshaw\WatsonVisualRecognition\Tests\Commands;

use Bobbyshaw\WatsonVisualRecognition\Client;
use Bobbyshaw\WatsonVisualRecognition\Commands\CreateClassifierCommand;
use Bobbyshaw\WatsonVisualRecognition\Tests\Base;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

class CreateClassifierCommandTest extends Base
{
    public function testCommand()
    {
        $container = [];
        $response = $this->getMockHttpResponse('CreateClassifierSuccess.txt');
        $httpClient = $this->getMockHttpClientWithHistoryAndResponses($container, [$response]);

        $arguments = [
            'username' => 'test',
            'password' => 'test',
            'positive_examples' => 'Tests/images/butterfly-positive.zip',
            'negative_examples' => 'Tests/images/butterfly-negative.zip',
            'name' => 'butterfly',
            '--version-date' => '2016-01-01'
        ];
        $input = new ArrayInput($arguments);

        $output = new BufferedOutput();

        $command = new CreateClassifierCommand(null, new Client($httpClient));
        $command->run($input, $output);

        $this->assertEquals('classifier:create', $command->getName());

        $correctOutput = file_get_contents('Tests/Mock/Commands/CreateClassifierSuccess.txt');

        $this->assertEquals($correctOutput, $output->fetch());
    }
}
