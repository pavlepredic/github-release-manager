<?php

namespace PavlePredic\GithubReleaseManager\Filter;

class DraftFilter implements FilterInterface
{
    public function matches(array $release) : bool
    {
        return $release['draft'] === true;
    }
}
