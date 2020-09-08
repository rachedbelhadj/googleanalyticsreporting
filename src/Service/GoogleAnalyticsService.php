<?php


namespace App\Service;


use Google_Client;
use Google_Service_Analytics;
use Google_Service_Analytics_GaData;
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

    public function isAccessTokenExpired(): bool
    {
        return $this->client->isAccessTokenExpired();
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

    public function getChartResults(string $profileId, string $metric): Google_Service_Analytics_GaData
    {
        return $this->analytics->data_ga->get(
            'ga:' . $profileId,
            '30daysAgo',
            'today',
            $metric,
            array(
                'dimensions' => 'ga:Date'
            )
        );
    }

    function buildChartArray(Google_Service_Analytics_GaData $results): array
    {
        $array = [];
        if (count($results->getRows()) > 0) {
            $rows = $results->getRows(); // On compte les lignes
            $array=[["Date","Pages Vues","Visiteurs","Visites"]]; // Initialisation du tableau avec les nomn des "colonnes"
            foreach($rows as $date){ // Parcours des dates
                $datejour = substr($date[0],-2,2)."/".substr($date[0],-4,2)."/".substr($date[0],0,4); // On formatte la date (pour être joli à l'affichage)
                array_push($array,[$datejour,(int)$date[1],(int)$date[2],(int)$date[3]]); // On ajoute la date et les données du jour au tableau
            }
        }

        return $array;
    }
}