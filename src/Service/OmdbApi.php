<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Classe qui communique avec l'API OMBDAPI.com
 */
class OmdbApi
{
    private $httpClient;
    private $apiKey;

    public function __construct(HttpClientInterface $httpClient, $apiKey)
    {
        $this->httpClient = $httpClient;

        $this->apiKey = $apiKey;
    }

    /**
     * Exécuter une requête pour une titre donné
     */
    public function fetch(string $title)
    {
        $response = $this->httpClient->request(
            'GET',
            'http://www.omdbapi.com/',
            [
                'query' => [
                    'apiKey' => $this->apiKey,
                    't' => $title,
                ]
            ]
        );

        $content = $response->toArray();

        return $content;
    }

    /**
     * Récupère le poster d'un film donné
     * 
     * @param string $title Titre du film à trouver
     * 
     * @return string|null URL du poster ou null si non trouvé
     */
    public function fetchPoster(string $title)
    {
        $content = $this->fetch($title);

        if (!array_key_exists('Poster', $content)) {
            return null;
        }

        return $content['Poster'];
    }
}