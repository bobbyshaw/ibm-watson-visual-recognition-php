<?php

namespace Bobbyshaw\WatsonVisualRecognition\Commands;

use Bobbyshaw\WatsonVisualRecognition\Message\ClassifierResponse;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use Bobbyshaw\WatsonVisualRecognition\Classifier;

/**
 * Command to get a classifier detail
 *
 * @package Bobbyshaw\WatsonVisualRecognition\Commands
 * @author Tom Robertshaw <me@tomrobertshaw.net>
 */
class GetClassifierCommand extends BaseCommand
{
    /**
     * Configure command
     */
    protected function configure()
    {
        $this
            ->setName('classifier:get')
            ->setDescription('Get Classifier')
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
            ->addArgument(
                'classifier_id',
                InputArgument::REQUIRED,
                'Classifier ID'
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

        $request = $this->client->getClassifier(['classifier_id' => $input->getArgument('classifier_id')]);

        /** @var ClassifierResponse $response */
        $response = $request->send();

        /** @var Classifier $classifier */
        $classifier = $response->getClassifier();

        $headers = ['ID', 'Name', 'Owner', 'Created At'];
        $tableRows = [
            [
                $classifier->getId(),
                $classifier->getName(),
                $classifier->getOwner(),
                $classifier->getCreated()->format('Y-m-d H:i:s')
            ]
        ];

        $table = new Table($output);
        $table->setHeaders($headers)->setRows($tableRows)->render();
    }
}
