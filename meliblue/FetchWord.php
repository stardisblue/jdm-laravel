<?php
/**
 * Created by PhpStorm.
 * User: MELY
 * Date: 9/28/2017
 * Time: 2:36 PM
 */

namespace Meliblue;


class FetchWord
{

    private static $baseUrl = "http://www.jeuxdemots.org/rezo-dump.php?gotermsubmit=Chercher";

    public static function createURL(String $word, $relation = ""): String
    {
        return self::$baseUrl . "&gotermrel=" . $word . "&rel=" . $relation;
    }

    static function getHTML(String $url): array
    {
        $request = curl_init($url);
        curl_setopt($request, CURLOPT_URL, $url);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        $raw = curl_exec($request);
        $info = curl_getinfo($request);
        $http_result = $info['http_code'];
        curl_close($request);

        $domDoc = new \DOMDocument('1.0', 'ISO-8859-1');
        @$domDoc->loadHTML($raw);
        $output = $domDoc->getElementsByTagName('code')->item(0);


        return ['out' => $output, 'raw' => $raw, 'code' => $http_result, 'info' => $info];
    }

    static function getFile(String $url)
    {
        return fopen($url, 'r');
    }

    static function fetchFile(String $name, $relation = null)
    {
        return self::getFile(self::createURL($name, $relation));
    }

    static function fetch(String $name, $relation = null)
    {
        return self::getHTML(self::createURL($name, $relation));
    }

    static function toXML(String $output)
    {
        return simplexml_load_string($output);
    }
}