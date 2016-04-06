<?php

namespace Bobbyshaw\WatsonVisualRecognition\Commands;

use Bobbyshaw\WatsonVisualRecognition\Client;
use Bobbyshaw\WatsonVisualRecognition\ClientInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class BaseCommand
 * @package Bobbyshaw\WatsonVisualRecognition\Commands
 */
abstract class BaseCommand extends Command
{

    /** @var ClientInterface  */
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
     * Configure command
     */
    protected function configure()
    {
        $this->addArgument(
                'username',
                InputArgument::REQUIRED,
                'IBM Watson Service credentials username.'
            )
            ->addArgument(
                'password',
                InputArgument::REQUIRED,
                'IBM Watson Service credentials password.'
            )
            ->addOption(
                'version-date',
                '-d',
                InputOption::VALUE_REQUIRED,
                'API version date, defaults to current date i.e. latest release'
            );
    }

    /**
     * @return ClientInterface
     */
    public function getDefaultClient()
    {
        return new Client();
    }

    /**
     * @return ClientInterface
     */
    public function getClient()
    {
        return $this->client;
    }
}
