<?php

namespace Bobbyshaw\WatsonVisualRecognition\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use Bobbyshaw\WatsonVisualRecognition\Client;
use Bobbyshaw\WatsonVisualRecognition\Classifier;

/**
 * Command to get a list of classifiers
 *
 * @package Bobbyshaw\WatsonVisualRecognition\Commands
 * @author Tom Robertshaw <me@tomrobertshaw.net>
 */
class GetClassifiersCommand extends Command
{
    /**
     * Configure command
     */
    protected function configure()
    {
        $this
            ->setName('classifiers:get')
            ->setDescription('Get Classifiers')
            ->addArgument(
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
                'major-api-version',
                '-a',
                InputOption::VALUE_REQUIRED,
                'Major API version, defaults to v2'
            )
            ->addOption(
                'version-date',
                '-d',
                InputOption::VALUE_REQUIRED,
                'API version date, defaults to current date i.e. latest release'
            );
    }

    /**
     * Execute command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = array(
            'username' => $input->getArgument('username'),
            'password' => $input->getArgument('password'),
        );

        if ($majorApiVerson = $input->getOption('major-api-version')) {
            $config['major-api-version'] = $majorApiVerson;
        }

        if ($version = $input->getOption('version-date')) {
            $config['version'] = $version;
        }

        $client = new Client($config);

        $classifiers = $client->getClassifiers();

        $tableRows = array();
        /** @var Classifier $classifier */
        foreach ($classifiers as $classifier) {
            $tableRows[] = array($classifier->getId(), $classifier->getName());
        }

        $table = new Table($output);
        $table->setHeaders(array('ID', 'Name'))->setRows($tableRows)->render();
    }
}
