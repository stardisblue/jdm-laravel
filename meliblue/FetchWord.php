<?php namespace Meliblue;


use GuzzleHttp\Client;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;

class FetchWord
{
    const RELATION_NONE = -1;

    private static $baseUrl = "http://www.jeuxdemots.org/rezo-dump.php";

    /**
     * @param string $name
     * @param string $relation
     * @return ResponseInterface
     */
    static function fetch(string $name, $relation = ''): ResponseInterface
    {
        return (new Client())->request('GET', self::$baseUrl,
            ['query' => ['gotermrel' => utf8_decode($name), 'rel' => $relation, 'output' => 0]]);
    }

    /**
     * @param string $name
     * @param string $relation
     * @return PromiseInterface
     */
    static function fetchAsync(string $name, $relation = ''): PromiseInterface
    {
        return (new Client())->requestAsync('GET', self::$baseUrl,
            ['query' => ['gotermrel' => utf8_decode($name), 'rel' => $relation, 'output' => 0]]);
    }
}