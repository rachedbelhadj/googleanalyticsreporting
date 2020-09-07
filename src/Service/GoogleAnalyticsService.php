<?php


namespace App\Service;


use Google_Client;
use Google_Service_Analytics;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class GoogleAnalyticsService
{

    /**
     * @var Google_Client
     */
    private $client;

    /**
     * @var Google_Service_Analytics
     */
    public $analytics;

    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(
        SessionInterface $session,
        Google_Client $client,
        string $clientId,
        string $clientSecret,
        string $developerKey,
        string $redirectUri,
        string $scopes
    ) {
        $this->session = $session;
        $this->client = $client;
        $this->client->setClientId($clientId);
        $this->client->setClientSecret($clientSecret);
        $this->client->setDeveloperKey($developerKey);
        $this->client->setRedirectUri($redirectUri);
        $scopes = explode(',', $scopes);
        $this->client->setScopes($scopes);
        $this->analytics = new Google_Service_Analytics($this->client);
    }

    public function isLoggedIn(): bool
    {
        if ($this->session->has('token') === true) {
            $this->client->setAccessToken($this->session->get('token'));
            return true;
        }

        return false;
    }

    public function getAccessToken(): array
    {
        return $this->client->getAccessToken();
    }

    public function login($code): array
    {
        $this->client->authenticate($code);
        $token = $this->client->getAccessToken();
        $this->session->set('token', $token);

        return $token;
    }

    public function getLoginUrl(): string
    {
        return $this->client->createAuthUrl();
    }
}