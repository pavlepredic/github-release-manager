<?php

namespace PavlePredic\GithubReleaseManager\Filter;

interface FilterInterface
{
    public function matches(array $release) : bool;
}
