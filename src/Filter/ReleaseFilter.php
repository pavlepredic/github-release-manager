<?php

namespace PavlePredic\GithubReleaseManager\Filter;

class ReleaseFilter implements FilterInterface
{
    public function matches(array $release) : bool
    {
        return $release['prerelease'] === false && $release['draft'] === false;
    }
}
