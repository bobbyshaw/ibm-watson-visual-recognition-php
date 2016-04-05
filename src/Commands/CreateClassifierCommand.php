<?php

namespace Bobbyshaw\WatsonVisualRecognition\Commands;

use Bobbyshaw\WatsonVisualRecognition\Classifier;
use Bobbyshaw\WatsonVisualRecognition\Message\ClassifierResponse;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

/**
 * Command to train a new classifier
 *
 * @package Bobbyshaw\WatsonVisualRecognition\Commands
 * @author Tom Robertshaw <me@tomrobertshaw.net>
 */
class CreateClassifierCommand extends BaseCommand
{
    /**
     * Configure command
     */
    protected function configure()
    {
        $this
            ->setName('classifier:create')
            ->setDescription('Train a new classifier')
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
                'positive_examples',
                InputArgument::REQUIRED,
                'Zip of positive images.'
            )
            ->addArgument(
                'negative_examples',
                InputArgument::REQUIRED,
                'Zip of negative images.'
            )
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'New classifier name.'
            )
            ->addOption(
                'version-date',
                '-d',
                InputOption::VALUE_REQUIRED,
                'API version date, defaults to latest release'
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

        $params = [
            'positive_examples' => $input->getArgument('positive_examples'),
            'negative_examples' => $input->getArgument('negative_examples'),
            'name' => $input->getArgument('name')

        ];

        $this->client->initialize($config);
        $request = $this->client->createClassifier($params);

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
