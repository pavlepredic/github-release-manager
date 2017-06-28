<?php

namespace PavlePredic\GithubReleaseManager\Command;

use GuzzleHttp\Client;
use PavlePredic\GithubReleaseManager\Service\GithubApiClient;
use PavlePredic\GithubReleaseManager\Service\ReleaseFilter;
use Symfony\Component\Console\Exception\InvalidOptionException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class DeleteReleasesCommand extends BaseReleasesCommand
{
    protected function configure()
    {
        $this
            ->setName('del')
            ->setDescription('Deletes Github releases')
            ->addOption('force', '-f', InputOption::VALUE_NONE, 'Don\'t ask for confirmation')
            ->addOption('with-tags', null, InputOption::VALUE_NONE, 'Delete the associated tag as well')
        ;

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $token = $input->getOption('token');
        $repo = $input->getArgument('repo');
        $force = $input->getOption('force');
        $withTags = $input->getOption('with-tags');

        if (!$token) {
            throw new InvalidOptionException('You must provide a token for `del` operation');
        }

        $client = new GithubApiClient(new Client(), $token);

        $releases = $client->fetchAllReleases($repo);
        $filtered = $this->filterReleases($input, $releases);

        foreach ($filtered as $release) {
            $helper = $this->getHelper('question');
            $q = 'Delete this release';
            if ($withTags) {
                $q.= ' and the associated tag';
            }

            $question = new ConfirmationQuestion(sprintf('%s? (Y/N)', $q), false);
            $this->printReleases($output, [$release]);
            if ($force || $helper->ask($input, $output, $question)) {
                $client->deleteRelease($repo, $release['id']);
                if ($withTags) {
                    $client->deleteTag($repo, $release['tag_name']);
                }
                $output->writeln('Deleted');
            } else {
                $output->writeln('Skipping');
            }
        }
    }
}
