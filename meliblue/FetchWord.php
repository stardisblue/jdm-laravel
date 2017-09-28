<?php
/**
 * Created by PhpStorm.
 * User: MELY
 * Date: 9/28/2017
 * Time: 2:36 PM
 */

namespace Meliblue;


use GuzzleHttp\Client;

class FetchWord
{

    private static $baseUrl = "http://www.jeuxdemots.org/rezo-dump.php?gotermsubmit=Chercher";

    static function fetch(String $name, $relation = '')
    {
        $client = new Client();

        $response = $client->request('GET', self::$baseUrl,
            ['query' => ['gotermrel' => $name, 'rel' => $relation]]);

        $contents = $response->getBody()->getContents();
        $code = $response->getStatusCode();
        $reason = $response->getReasonPhrase();

        $domDoc = new \DOMDocument('1.0', 'ISO-8859-1');
        @$domDoc->loadHTML($contents);

        $output = $domDoc->getElementsByTagName('code')->item(0);

        return ['out' => $output, 'code' => $code, 'reason' => $reason];
    }

}