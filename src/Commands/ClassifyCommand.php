<?php

namespace Bobbyshaw\WatsonVisualRecognition\Commands;

use Bobbyshaw\WatsonVisualRecognition\Classifier;
use Bobbyshaw\WatsonVisualRecognition\Client;
use Bobbyshaw\WatsonVisualRecognition\Image;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

/**
 * Command to get classify image(s)
 *
 * @package Bobbyshaw\WatsonVisualRecognition\Commands
 * @author Tom Robertshaw <me@tomrobertshaw.net>
 */
class ClassifyCommand extends Command
{
    /**
     * Configure command
     */
    protected function configure()
    {
        $this
            ->setName('classifiers:classify')
            ->setDescription('Use classifiers to classify image')
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
                'images',
                InputArgument::REQUIRED,
                'Individual or zip of images.'
            )
            ->addOption(
                'classifiers',
                '-c',
                InputOption::VALUE_REQUIRED,
                'Classifiers that should be tested against'
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
        $config = [
            'username' => $input->getArgument('username'),
            'password' => $input->getArgument('password'),
        ];

        if ($majorApiVerson = $input->getOption('major-api-version')) {
            $config['major-api-version'] = $majorApiVerson;
        }

        if ($version = $input->getOption('version-date')) {
            $config['version'] = $version;
        }

        $client = new Client($config);

        $classifierIds = null;
        if ($input->getOption('classifiers')) {
            $classifierIds = explode(',', $input->getOption('classifiers'));
        }

        $images = $client->classify($input->getArgument('images'), $classifierIds);

        $tableRows = [];
        /** @var Image $image */
        foreach ($images as $image) {
            /** @var Classifier $classifier */
            foreach ($image->getClassifiers() as $classifier) {
                $tableRows[] = [
                    $image->getName(), $classifier->getId(), $classifier->getName(), $classifier->getScore()
                ];
            }
        }

        $table = new Table($output);
        $table->setHeaders(['Image', 'Classifier ID', 'Classifier Name', 'Classifier Score'])
            ->setRows($tableRows)->render();
    }
}
