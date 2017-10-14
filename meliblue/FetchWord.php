<?php

namespace Meliblue;


use GuzzleHttp\Client;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;

class FetchWord
{

    private static $baseUrl = "http://www.jeuxdemots.org/rezo-dump.php";

    /**
     * @param string $name
     * @param string $relation
     * @return ResponseInterface
     */
    static function fetch(string $name, $relation = ''): ResponseInterface
    {
        $client = new Client();

        return $response = $client->request('GET', self::$baseUrl,
            ['query' => ['gotermrel' => $name, 'rel' => $relation, 'output' => 0]]);
    }

    /**
     * @param string $name
     * @param string $relation
     * @return PromiseInterface
     */
    static function fetchAsync(string $name, $relation = ''): PromiseInterface
    {
        $client = new Client();

        return $client->requestAsync('GET', self::$baseUrl,
            ['query' => ['gotermrel' => $name, 'rel' => $relation, 'output' => 0]]);
    }
}