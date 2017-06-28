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
        $token = $input->getArgument('token');
        $repo = $input->getArgument('repo');

        $client = new GithubApiClient(new Client(), $token);

        $releases = $client->fetchAllReleases($repo);
        $releases = $this->filterReleases($input, $releases);

        foreach ($releases as $release) {
            $this->printRelease($output, $release);
        }
    }
}
