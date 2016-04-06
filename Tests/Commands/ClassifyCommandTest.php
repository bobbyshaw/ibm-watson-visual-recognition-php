<?php

namespace Bobbyshaw\WatsonVisualRecognition\Tests\Commands;

use Bobbyshaw\WatsonVisualRecognition\Client;
use Bobbyshaw\WatsonVisualRecognition\Commands\ClassifyCommand;
use Bobbyshaw\WatsonVisualRecognition\Tests\Base;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

class ClassifyCommandTest extends Base
{
    public function testCommand()
    {
        $container = [];
        $response = $this->getMockHttpResponse('ClassifySuccess.txt');
        $httpClient = $this->getMockHttpClientWithHistoryAndResponses($container, [$response]);

        $classifiers = 'photo_1572731175,Flower_Scene,Butterfly,Picket_Fence,Flower,bright_496607388,Insect,' .
            'Florist_Shop,living_room_1007650764,Violet,watermarked_2070418028,inside_402842804,Aquarium,Ballooning,' .
            'Arthropod,swimmingpool_109438771,Invertebrate,blurry_786219730,Shoes,sea_524758387,Graduation,' .
            'blue_sky_2056231781,Popcorn,Hummingbird,Cormorant,blurry_786219730,blue_sky_2056231781,Blue,' .
            'Military_Plane,Statue_of_Liberty,Fish,Rainbow,White,watermarked_2070418028,Red,Hang_Gliding,Crevasse,' .
            'Frost,Air_Sport,Bird,Vertebrate,Animal,Capitol,Ice_Scene,Ski_Jumping,photo_1114291327';

        $arguments = [
            'username' => 'test',
            'password' => 'test',
            'images'   => 'Tests/images/Cosmos-Flower-And-Hummingbird.zip',
            '--classifiers' => $classifiers,
            '--version-date' => '2016-01-01'
        ];
        $input = new ArrayInput($arguments);
        
        $output = new BufferedOutput();

        $command = new ClassifyCommand(null, new Client($httpClient));
        $command->run($input, $output);

        $this->assertEquals('classifiers:classify', $command->getName());

        $correctOutput = file_get_contents('Tests/Mock/Commands/ClassifySuccess.txt');

        $this->assertEquals($correctOutput, $output->fetch());
    }
}
