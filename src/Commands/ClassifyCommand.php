<?php

namespace Bobbyshaw\WatsonVisualRecognition\Commands;

use Bobbyshaw\WatsonVisualRecognition\Classifier;
use Bobbyshaw\WatsonVisualRecognition\Image;
use Bobbyshaw\WatsonVisualRecognition\Message\ClassifyResponse;
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
class ClassifyCommand extends BaseCommand
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

        $params = ['images_file' => $input->getArgument('images')];

        if ($input->getOption('classifiers')) {
            $params['classifier_ids'] = explode(',', $input->getOption('classifiers'));
        }

        $this->client->initialize($config);
        $request = $this->client->classify($params);

        /** @var ClassifyResponse $response */
        $response = $request->send();
        $images = $response->getImages();

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
