<?php

namespace PavlePredic\GithubReleaseManager\Command;

use GuzzleHttp\Client;
use PavlePredic\GithubReleaseManager\Service\GithubApiClient;
use PavlePredic\GithubReleaseManager\Service\ReleaseFilter;
use Symfony\Component\Console\Exception\InvalidOptionException;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class DeleteReleasesCommand extends BaseReleasesCommand
{
    protected function configure()
    {
        $this
            ->setName('rm')
            ->setDescription('Deletes Github releases')
            ->addOption('with-tags', null, InputOption::VALUE_NONE, 'Delete the associated tag as well')
        ;

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $token = $input->getOption('token');
        $repo = $input->getArgument('repo');
        $withTags = $input->getOption('with-tags');

        if (!$token) {
            throw new InvalidOptionException('You must provide a token for this operation');
        }

        $client = new GithubApiClient(new Client(), $token);

        $releases = $client->fetchAllReleases($repo);
        $releases = $this->filterReleases($input, $releases);

        if (empty($releases)) {
            $output->writeln('No releases matching the criteria');
            return;
        }

        $this->printReleases($output, $releases);

        $helper = $this->getHelper('question');
        $q = 'Delete these releases';
        if ($withTags) {
            $q.= ' and the associated tags';
        }

        $question = new ConfirmationQuestion(sprintf('%s? (Y/N)', $q), false);
        if (!$helper->ask($input, $output, $question)) {
            $output->writeln('Aborting');
            return;
        }

        $progressBar = new ProgressBar($output, count($releases));
        $progressBar->start();
        foreach ($releases as $release) {
            $client->deleteRelease($repo, $release['id']);

            if ($withTags && !$release['draft']) {
                $client->deleteTag($repo, $release['tag_name']);
            }
            $progressBar->advance();
        }

        $progressBar->finish();
        $output->writeln('');
    }
}
