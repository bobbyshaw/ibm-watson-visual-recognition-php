<?php

namespace Bobbyshaw\WatsonVisualRecognition\Tests\Commands;

use Bobbyshaw\WatsonVisualRecognition\Client;
use Bobbyshaw\WatsonVisualRecognition\Commands\DeleteClassifierCommand;
use Bobbyshaw\WatsonVisualRecognition\Tests\Base;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

class DeleteClassifierCommandTest extends Base
{
    /**
     * Test that the command outputs a success info message.
     */
    public function testCommand()
    {
        $container = [];
        $httpClient = $this->getMockHttpClientWithHistoryAndResponses($container, [new Response(200, [], '{}')]);

        $arguments = [
            'username' => 'test',
            'password' => 'test',
            'classifier_id' => 'Magenta',
            '--version-date' => '2016-01-01',
        ];
        $input = new ArrayInput($arguments);
        $output = new BufferedOutput();

        $command = new DeleteClassifierCommand(null, new Client($httpClient));
        $command->run($input, $output);

        $this->assertEquals('classifier:delete', $command->getName());

        $correctOutput = file_get_contents('Tests/Mock/Commands/DeleteClassifierSuccess.txt');

        $this->assertEquals($correctOutput, $output->fetch());
    }

    public function testCommandFail()
    {
        $container = [];
        $response = $this->getMockHttpResponse('DeleteClassifierFail.txt');
        $httpClient = $this->getMockHttpClientWithHistoryAndResponses($container, [$response]);

        $arguments = [
            'username' => 'test',
            'password' => 'test',
            'classifier_id' => 'Sunset',
            '--version-date' => '2016-01-01',
        ];
        $input = new ArrayInput($arguments);
        $output = new BufferedOutput();

        $command = new DeleteClassifierCommand(null, new Client($httpClient));
        $command->run($input, $output);

        $this->assertEquals('classifier:delete', $command->getName());

        $this->assertEquals("Cannot delete classifier: Sunset\n", $output->fetch());
    }
}
