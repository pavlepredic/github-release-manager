<?php

namespace PavlePredic\GithubReleaseManager\Filter;

class CreatedBeforeFilter implements FilterInterface
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
        return date_create($release['created_at']) < $this->cutoff;
    }
}
