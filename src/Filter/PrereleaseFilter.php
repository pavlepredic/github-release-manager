<?php

namespace PavlePredic\GithubReleaseManager\Filter;

class PrereleaseFilter implements FilterInterface
{
    public function matches(array $release) : bool
    {
        return $release['prerelease'] === true;
    }
}
