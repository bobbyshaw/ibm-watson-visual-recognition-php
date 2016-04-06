<?php

namespace Bobbyshaw\WatsonVisualRecognition\Commands;

use Bobbyshaw\WatsonVisualRecognition\Message\DeleteClassifierRequest;
use Bobbyshaw\WatsonVisualRecognition\Message\Response;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
        parent::configure();

        $this
            ->setName('classifier:delete')
            ->setDescription('Delete Classifier')
            ->addArgument(
                'classifier_id',
                InputArgument::REQUIRED,
                'Classifier ID'
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
