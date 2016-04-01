<?php

namespace Bobbyshaw\WatsonVisualRecognition\Commands;

use Bobbyshaw\WatsonVisualRecognition\Message\ClassifiersResponse;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use Bobbyshaw\WatsonVisualRecognition\Classifier;

/**
 * Command to get a list of classifiers
 *
 * @package Bobbyshaw\WatsonVisualRecognition\Commands
 * @author Tom Robertshaw <me@tomrobertshaw.net>
 */
class GetClassifiersCommand extends BaseCommand
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
        $config = [
            'username' => $input->getArgument('username'),
            'password' => $input->getArgument('password'),
        ];

        if ($version = $input->getOption('version-date')) {
            $config['version'] = $version;
        }
        
        $this->client->initialize($config);

        $request = $this->client->getClassifiers();

        /** @var ClassifiersResponse $response */
        $response = $request->send();

        $classifiers = $response->getClassifiers();

        $tableRows = [];
        /** @var Classifier $classifier */
        foreach ($classifiers as $classifier) {
            $tableRows[] = [$classifier->getId(), $classifier->getName()];
        }

        $table = new Table($output);
        $table->setHeaders(['ID', 'Name'])->setRows($tableRows)->render();
    }
}
