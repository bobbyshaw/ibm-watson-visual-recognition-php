<?php

namespace Bobbyshaw\WatsonVisualRecognition\Tests\Commands;

use Bobbyshaw\WatsonVisualRecognition\ClientInterface;
use Bobbyshaw\WatsonVisualRecognition\Commands\BaseCommand;
use Bobbyshaw\WatsonVisualRecognition\Tests\Base;

/**
 * Class BaseCommandTest
 * @package Bobbyshaw\WatsonVisualRecognition\Tests\Commands
 */
class BaseCommandTest extends Base
{

    public function testConstructor()
    {
        /** @var BaseCommand $base */
        $base = $this->getMockForAbstractClass(BaseCommand::class, ['test']);
        $this->assertInstanceOf(ClientInterface::class, $base->getClient());
    }
}
