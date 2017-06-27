<?php

namespace PavlePredic\GithubReleaseManager\Command;

use PavlePredic\GithubReleaseManager\Filter\AuthorFilter;
use PavlePredic\GithubReleaseManager\Filter\PublishedBeforeFilter;
use PavlePredic\GithubReleaseManager\Service\ReleaseFilter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

abstract class BaseReleasesCommand extends Command
{
    protected function configure()
    {
        $this
            ->addArgument('token', InputArgument::REQUIRED, 'Github token')
            ->addArgument('repo', InputArgument::REQUIRED, 'Github repository (in user/repo format)')
            ->addOption('before', 'b', InputOption::VALUE_OPTIONAL, 'Filter releases older than date')
            ->addOption('author', 'a', InputOption::VALUE_OPTIONAL, 'Filter releases by author')
        ;
    }

    protected function filterReleases(InputInterface $input, array $releases) : array
    {
        $filters = [];
        if ($cutoff = $input->getOption('before')) {
            $filters[] = new PublishedBeforeFilter(date_create($cutoff));
        }

        if ($author = $input->getOption('author')) {
            $filters[] = new AuthorFilter($author);
        }

        foreach ($filters as $filter) {
            $filtered = [];
            foreach ($releases as $release) {
                if ($filter->matches($release)) {
                    $filtered[] = $release;
                }
            }
            $releases = $filtered;
        }

        return $releases;
    }

    protected function printRelease(OutputInterface $output, array $release) : void
    {
        $output->writeln(sprintf(
            'Release: "%s", Author: "%s", Name: "%s", Tag: "%s", Created: "%s", Published: "%s"',
            $release['id'],
            $release['author']['login'],
            $release['name'],
            $release['tag_name'],
            $release['created_at'],
            $release['published_at']
        ));
    }

}
