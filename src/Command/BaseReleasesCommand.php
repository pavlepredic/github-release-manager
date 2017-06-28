<?php

namespace PavlePredic\GithubReleaseManager\Command;

use PavlePredic\GithubReleaseManager\Filter\AuthorFilter;
use PavlePredic\GithubReleaseManager\Filter\DraftFilter;
use PavlePredic\GithubReleaseManager\Filter\PrereleaseFilter;
use PavlePredic\GithubReleaseManager\Filter\PublishedBeforeFilter;
use PavlePredic\GithubReleaseManager\Filter\CreatedBeforeFilter;
use PavlePredic\GithubReleaseManager\Filter\ReleaseFilter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

abstract class BaseReleasesCommand extends Command
{
    protected function configure()
    {
        $this
            ->addArgument('repo', InputArgument::REQUIRED, 'Github repository (in user/repo format)')
            ->addOption('created-before', null, InputOption::VALUE_REQUIRED, 'Filter releases created before the provided date')
            ->addOption('published-before', null, InputOption::VALUE_REQUIRED, 'Filter releases published before the provided date')
            ->addOption('author', 'a', InputOption::VALUE_REQUIRED, 'Filter releases by author')
            ->addOption('token', 't', InputOption::VALUE_REQUIRED, 'Github token')
            ->addOption('type', null, InputOption::VALUE_REQUIRED, 'Github release type (one of release, prerelease and draft')
        ;
    }

    protected function filterReleases(InputInterface $input, array $releases) : array
    {
        $filters = [];
        if ($cutoff = $input->getOption('created-before')) {
            $filters[] = new CreatedBeforeFilter(date_create($cutoff));
        }

        if ($cutoff = $input->getOption('published-before')) {
            $filters[] = new PublishedBeforeFilter(date_create($cutoff));
        }

        if ($author = $input->getOption('author')) {
            $filters[] = new AuthorFilter($author);
        }

        if ($type = $input->getOption('type')) {
            switch ($type) {
                case 'release' :
                    $filters[] = new ReleaseFilter();
                    break;
                case 'prerelease' :
                    $filters[] = new PrereleaseFilter();
                    break;
                case 'draft' :
                    $filters[] = new DraftFilter();
                    break;
            }
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

    protected function printReleases(OutputInterface $output, array $releases) : void
    {
        $table = new Table($output);
        $table->setHeaders([
            '#',
            'Release ID',
            'Author',
            'Name',
            'Type',
            'Tag',
            'Created at',
            'Published at',
        ]);

        $i = 0;
        foreach ($releases as $release) {
            $type = 'release';
            if ($release['draft']) {
                $type = 'draft';
            } elseif ($release['prerelease']) {
                $type = 'prerelease';
            }

            $table->addRow([
                ++$i,
                $release['id'],
                $release['author']['login'],
                $release['name'],
                $type,
                $release['tag_name'],
                $release['created_at'],
                $release['published_at']
            ]);
        }

        $table->render();
    }

}
