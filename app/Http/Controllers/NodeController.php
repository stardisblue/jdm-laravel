<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Meliblue\ElasticBlue\Models\ElasticNode;
use Meliblue\ElasticBlue\Models\ElasticNodeCache;
use Meliblue\ElasticBlue\Models\ElasticRelationIn;
use Meliblue\ElasticBlue\Models\ElasticRelationOut;
use Meliblue\FetchWord;
use Meliblue\Models\CleanNode;
use Meliblue\WordParser;

class NodeController extends Controller
{

    public function search(Request $request, int $page = 0)
    {
        $request->validate([
            'q' => 'required|string',
        ]);

        $query = $request->input('q');

        $results = ElasticNode::nodeSearch($query, $page);
        if ($results === null) {
            $results = ElasticNode::nodeRegexpSearch($query, $page);
            if ($results != null) {
                return view('node.search', ["results" => $results]);
            }
            return redirect()->route('node', ['word' => $query]);
        }

        if (sizeof($results) === 1) {
            return redirect()->route('node', ['word' => $results[0]['name']]);
        }

        return view('node.search', ["results" => $results]);
    }

    public function display(Request $request)
    {
        $request->validate([
            'word' => 'required|string',
        ]);

        $word = $request->input('word');

        $nodeCache = ElasticNodeCache::getNode($word, 1);

        // if the word isn't in our database
        if ($nodeCache === null) {
            // we fetch it from jeuxdemot
            $response = FetchWord::fetch($word);

            // we extract the content
            $parsed = WordParser::parse($response);
            $reason = $parsed->getReason();

            // if the file is too big
            if ($parsed->getCode() === 413) {
                $response = FetchWord::fetch($word, FetchWord::RELATION_NONE);
                $parsed = WordParser::parse($response);

                // we get the raw node
                $rawNode = $parsed->getNode();

                $cleanNode = new CleanNode($rawNode);

                // we extract the node information from it
                $elasticNode = new ElasticNode();
                $elasticNode->setNode($cleanNode);
                $elasticNode->save();

                return redirect()->route('home')->withErrors(['reason' => $reason, 'word' => $word]);
            }

            // if the word exists
            if ($parsed->getCode() !== 404) {
                // we get the raw node
                $rawNode = $parsed->getNode();

                $cleanNode = new CleanNode($rawNode);

                // we extract the node information from it
                $elasticNode = new ElasticNode();
                $elasticNode->setNode($cleanNode);
                $elasticNode->save();

                // we extract the relations from it
                foreach ($cleanNode->relationTypes as $relationType) {
                    ElasticRelationIn::bulkCreate($cleanNode->id, $relationType->id, $relationType->relations['in']);
                    ElasticRelationOut::bulkCreate($cleanNode->id, $relationType->id, $relationType->relations['out']);
                }

                // then we trim the relation list down
                // danger, don't reverse this with the bulkcreate because it will only create the trimmed relations.
                $nodeCache = new ElasticNodeCache();
                $nodeCache->setNode($cleanNode);
                $nodeCache->save();
            } else {
                return redirect()->route('home')->withErrors(['reason' => $reason, 'word' => $word]);
            }
        }

        return view('node.single', ["node" => $nodeCache]);
    }
}