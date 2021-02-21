<?php

namespace App\Resources;

use App\Generics\TriviaInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class TriviaResource
 *
 * @author Mareks Galanskis
 */
class FragmentTriviaResource
{

    /**
     * File
     *
     * @var TriviaInterface
     */
    private $trivia;

    /**
     * Client
     *
     * @var Client
     */
    private $client;

    /**
     * Trivia resource constructor
     * @param Client $client
     * @param TriviaInterface $trivia
     */
    public function __construct(Client $client, TriviaInterface $trivia)
    {
        $this->client = $client;
        $this->trivia = $trivia;
    }

    /**
     * Get question
     *
     * @param int $targetAnswer
     *
     * @return string
     * @throws GuzzleException
     */
    public function getQuestion(int $targetAnswer): string
    {
        $response = $this->client->get($this->trivia->getPath($targetAnswer));

        return (string) $response->getBody();
    }

    /**
     * Get $lowerCasedName
     *
     * @return TriviaInterface
     */
    public function getTrivia(): TriviaInterface
    {
        return $this->trivia;
    }
}
