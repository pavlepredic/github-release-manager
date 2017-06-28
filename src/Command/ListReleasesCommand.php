<?php

namespace PavlePredic\GithubReleaseManager\Command;

use GuzzleHttp\Client;
use PavlePredic\GithubReleaseManager\Service\GithubApiClient;
use PavlePredic\GithubReleaseManager\Service\ReleaseFilter;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListReleasesCommand extends BaseReleasesCommand
{
    protected function configure()
    {
        $this
            ->setName('ls')
            ->setDescription('Lists Github releases')
        ;

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $repo = $input->getArgument('repo');
        $token = $input->getOption('token');

        $client = new GithubApiClient(new Client(), $token);

        $releases = $client->fetchAllReleases($repo);
        $releases = $this->filterReleases($input, $releases);

        if (empty($releases)) {
            $output->writeln('No releases matching the criteria');
            return;
        }

        $this->printReleases($output, $releases);
    }
}
