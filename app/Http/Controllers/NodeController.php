<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
        $validator = Validator::make($request->all(), [
            'q' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect('home')
                ->withErrors($validator)
                ->withInput();
        }

        $query = $request->input('q');

        $results = ElasticNode::nodeSearch($query, $page);

        if (sizeof($results) === 1) {
            return redirect()->route('node', ['word' => $results[0]['name']]);
        }

        return $results;
    }

    public function display(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'word' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect('home')
                ->withErrors($validator)
                ->withInput();
        }

        $word = $request->input('word');

        $nodeCache = ElasticNodeCache::getNode($word, 1);
        $reason = '';

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
            }
        }

        return view('node.single', ["node" => $nodeCache, "reason" => $reason]);
    }
}