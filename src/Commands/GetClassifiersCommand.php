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
                'api-verbose',
                '-a',
                InputOption::VALUE_NONE,
                'Enable verbose API request'
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

        $params = [];
        if ($verbose = $input->getOption('api-verbose')) {
            $params['verbose'] = $verbose;
        }
        $request = $this->client->getClassifiers($params);

        /** @var ClassifiersResponse $response */
        $response = $request->send();

        $classifiers = $response->getClassifiers();

        $tableRows = [];
        /** @var Classifier $classifier */
        foreach ($classifiers as $classifier) {
            $row = [$classifier->getId(), $classifier->getName()];
            if ($verbose) {
                $row = array_merge($row, [$classifier->getOwner(), $classifier->getCreated()->format('Y-m-d H:i:s')]);
            }

            $tableRows[] = $row;
        }

        $headers = ['ID', 'Name'];
        if ($verbose) {
            $headers = array_merge($headers, ['Owner', 'Created At']);
        }

        $table = new Table($output);
        $table->setHeaders($headers)->setRows($tableRows)->render();
    }
}
