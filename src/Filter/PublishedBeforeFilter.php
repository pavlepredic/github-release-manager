<?php

namespace PavlePredic\GithubReleaseManager\Filter;

class PublishedBeforeFilter implements FilterInterface
{
    /**
     * @var \DateTime
     */
    private $cutoff;

    public function __construct(\DateTime $cutoff)
    {
        $this->cutoff = $cutoff;
    }

    public function matches(array $release) : bool
    {
        return date_create($release['published_at']) < $this->cutoff;
    }
}
