<?php

namespace PavlePredic\GithubReleaseManager\Command;

use GuzzleHttp\Client;
use PavlePredic\GithubReleaseManager\Service\GithubApiClient;
use PavlePredic\GithubReleaseManager\Service\ReleaseFilter;
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
            ->addOption('force', 'f', InputOption::VALUE_OPTIONAL, 'Do not ask for confirmation', false)
        ;

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $token = $input->getArgument('token');
        $repo = $input->getArgument('repo');
        $force = filter_var($input->getOption('force'), FILTER_VALIDATE_BOOLEAN);

        $client = new GithubApiClient(new Client(), $token);

        $releases = $client->fetchAllReleases($repo);
        $filtered = $this->filterReleases($input, $releases);

        foreach ($filtered as $release) {
            $helper = $this->getHelper('question');
            $question = new ConfirmationQuestion('Delete this release? (Y/N)', false);
            $this->printReleases($output, [$release]);
            if ($force || $helper->ask($input, $output, $question)) {
                $client->deleteRelease($repo, $release['id']);
                $output->writeln('Deleted');
            } else {
                $output->writeln('Skipping');
            }
        }
    }
}
