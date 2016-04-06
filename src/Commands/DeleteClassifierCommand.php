<?php

namespace Bobbyshaw\WatsonVisualRecognition\Commands;

use Bobbyshaw\WatsonVisualRecognition\Message\DeleteClassifierRequest;
use Bobbyshaw\WatsonVisualRecognition\Message\Response;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use Bobbyshaw\WatsonVisualRecognition\Classifier;

/**
 * Command to delete a classifier
 *
 * @package Bobbyshaw\WatsonVisualRecognition\Commands
 * @author Tom Robertshaw <me@tomrobertshaw.net>
 */
class DeleteClassifierCommand extends BaseCommand
{
    /**
     * Configure command
     */
    protected function configure()
    {
        $this
            ->setName('classifier:delete')
            ->setDescription('Delete Classifier')
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

        /** @var DeleteClassifierRequest $request */
        $request = $this->client->deleteClassifier(['classifier_id' => $input->getArgument('classifier_id')]);

        /** @var Response $response */
        $response = $request->send();

        if ($response->isSuccessful()) {
            $output->writeln(sprintf("<info>Classifier %s successfully deleted.</info>", $request->getClassifierId()));
        } elseif ($error = $response->getErrorMessage()) {
            $output->writeln(sprintf("<error>%s</error>", $error));
        }
    }
}
