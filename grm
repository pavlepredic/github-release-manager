#!/usr/bin/env php
<?php
use PavlePredic\GithubReleaseManager\Command\DeleteReleasesCommand;
use PavlePredic\GithubReleaseManager\Command\ListReleasesCommand;

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new DeleteReleasesCommand());
$application->add(new ListReleasesCommand());

$application->run();
