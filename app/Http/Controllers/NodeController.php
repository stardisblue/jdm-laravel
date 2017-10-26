<?php

namespace App\Http\Controllers;


use App\NodeCache;
use Illuminate\Support\Facades\Cache;
use Meliblue\FetchWord;
use Meliblue\Node;
use Meliblue\WordParser;

class NodeController extends Controller
{
    public function display(string $word)
    {
        $node = NodeCache::searchByQuery(["match" => ['title' => $word]], null, null, 1);
        $reason = "";
        var_dump($node);
        die;

        // afficher un mot
        if ($node === null) {
            $response = FetchWord::fetch(utf8_decode($word));
            $parsed = WordParser::parse($response);
            $reason = $parsed->getReason();


            if ($parsed->getCode() === 413) {
                $response = FetchWord::fetch(utf8_decode($word), -1);
                $parsed = WordParser::parse($response);
            }

            if ($parsed->getCode() !== 404) {
                $rawNode = $parsed->getNode();
                $node = new Node($rawNode);
            }

            Cache::put($word, $node, 60);
        }

        return view('node.single', ["node" => $node, "reason" => $reason]);
    }

    public function card(string $word)
    {
        // afficher un mot
        if (Cache::has($word . ":card")) {
            $node = Cache::get($word . ":card");
        } else {
            $response = FetchWord::fetch(utf8_decode($word), -1);
            $parsed = WordParser::parse($response);
            $node = $parsed->getNode();
            $node->prepare();

            Cache::put($word . ":card", $node, 60);
        }

        return json_encode($node);
    }
}