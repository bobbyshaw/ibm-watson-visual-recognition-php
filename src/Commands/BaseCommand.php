<?php

namespace Bobbyshaw\WatsonVisualRecognition\Commands;

use Bobbyshaw\WatsonVisualRecognition\Client;
use Bobbyshaw\WatsonVisualRecognition\ClientInterface;
use Symfony\Component\Console\Command\Command;

/**
 * Class BaseCommand
 * @package Bobbyshaw\WatsonVisualRecognition\Commands
 */
abstract class BaseCommand extends Command
{

    /** @var Client|ClientInterface  */
    protected $client;

    /**
     * GetClassifiersCommand constructor.
     *
     * @param null|string $name
     * @param ClientInterface $client
     */
    public function __construct($name = null, ClientInterface $client = null)
    {
        $this->client = isset($client) ? $client : $this->getDefaultClient();

        parent::__construct($name);
    }

    /**
     * @return Client
     */
    public function getDefaultClient()
    {
        return new Client();
    }

    /**
     * @return Client|ClientInterface
     */
    public function getClient()
    {
        return $this->client;
    }
}
