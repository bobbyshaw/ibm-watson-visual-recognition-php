<?php

namespace Bobbyshaw\WatsonVisualRecognition\Tests\Commands;

use Bobbyshaw\WatsonVisualRecognition\Client;
use Bobbyshaw\WatsonVisualRecognition\Commands\GetClassifiersCommand;
use Bobbyshaw\WatsonVisualRecognition\Tests\Base;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

class GetClassifiersCommandTest extends Base
{
    /**
     * Test that the command outputs a table of classifiers
     */
    public function testCommand()
    {
        $container = [];
        $response = $this->getMockHttpResponse('GetClassifiersSuccess.txt');
        $httpClient = $this->getMockHttpClientWithHistoryAndResponses($container, [$response]);

        $arguments = [
            'username' => 'test',
            'password' => 'test',
            '--version-date' => '2016-01-01'
        ];
        $input = new ArrayInput($arguments);
        $output = new BufferedOutput();

        $command = new GetClassifiersCommand(null, new Client($httpClient));
        $command->run($input, $output);

        $this->assertEquals('classifiers:get', $command->getName());

        $correctOutput = file_get_contents('Tests/Mock/Commands/CLassifiersSuccess.txt');

        $this->assertEquals($correctOutput, $output->fetch());
    }
}
