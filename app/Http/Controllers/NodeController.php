<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Cache;
use Meliblue\FetchWord;
use Meliblue\WordParser;

class NodeController extends Controller
{
    public function display(string $word)
    {
        // afficher un mot
        if (Cache::get($word)) {
            $parsed = Cache::get($word);
        } else {
            $response = FetchWord::fetch(utf8_decode($word));
            $parsed = WordParser::parse($response['out']);
            $parsed->prepare();
            Cache::put($word, $parsed, 60);
        }


        return view('node.single', ["parsed" => $parsed]);
    }

    public function card(string $word)
    {
        // afficher un mot
        if (Cache::get($word)) {
            $parsed = Cache::get($word);
        } else {
            $response = FetchWord::fetch(utf8_decode($word), -1);
            $parsed = WordParser::parse($response['out']);
            $parsed->prepare();
            Cache::put($word, $parsed, 60);
        }


        return json_encode($parsed);
    }
}