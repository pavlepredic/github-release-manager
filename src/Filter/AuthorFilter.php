<?php

namespace PavlePredic\GithubReleaseManager\Filter;

class AuthorFilter implements FilterInterface
{
    /**
     * @var string
     */
    private $author;

    public function __construct(string $author)
    {
        $this->author = $author;
    }

    public function matches(array $release) : bool
    {
        return $release['author']['login'] === $this->author;
    }
}
