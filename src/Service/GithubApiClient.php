<?php

namespace PavlePredic\GithubReleaseManager\Service;

use GuzzleHttp\ClientInterface;
use PavlePredic\GithubReleaseManager\Filter\FilterInterface;

class GithubApiClient
{
    const BASE_URL = 'https://api.github.com';

    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * @var string
     */
    private $token;

    public function __construct(ClientInterface $httpClient, string $token, string $baseUrl = self::BASE_URL)
    {
        $this->httpClient = $httpClient;
        $this->token = $token;
        $this->baseUrl = $baseUrl;
    }

    public function getReleases(string $repo, int $page = 1) : array
    {
        $url = sprintf('repos/%s/releases?page=%s', $repo, $page);
        $response = $this->httpClient->request('GET', $url, $this->getDefaultOptions());
        return json_decode($response->getBody(), true);
    }

    public function deleteRelease(string $repo, string $id) : bool
    {
        $url = sprintf('repos/%s/releases/%s', $repo, $id);
        $response = $this->httpClient->request('DELETE', $url, $this->getDefaultOptions());
        return $response->getStatusCode() === 204;
    }

    public function fetchAllReleases(string $repo) : array
    {
        $allReleases = [];
        $page = 1;
        do {
            $releases = $this->getReleases($repo, $page);
            $allReleases = array_merge($allReleases, $releases);
            $page++;
        } while (count($releases));

        return $allReleases;
    }

    private function getDefaultOptions() : array
    {
        return [
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Authorization' => sprintf('token %s', $this->token),
            ],
        ];
    }

}
