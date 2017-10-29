<?php

namespace App\Http\Controllers;


use Meliblue\ElasticBlue\Models\ElasticNode;
use Meliblue\ElasticBlue\Models\ElasticNodeCache;
use Meliblue\ElasticBlue\Models\ElasticRelationIn;
use Meliblue\ElasticBlue\Models\ElasticRelationOut;
use Meliblue\FetchWord;
use Meliblue\Models\CleanNode;
use Meliblue\WordParser;

class NodeController extends Controller
{
    public function display(string $word)
    {
        $nodeCache = ElasticNodeCache::search(["match" => ['name' => $word]], 1)->getResults();
        $reason = '';

        // si le mot n'est pas encore enregistré
        if ($nodeCache === null) {
            // on recupere le contenu
            $response = FetchWord::fetch(utf8_decode($word));
            // on en extrait le noeud
            $parsed = WordParser::parse($response);
            $reason = $parsed->getReason();

            // if the file is too big
            if ($parsed->getCode() === 413) {
                $response = FetchWord::fetch(utf8_decode($word), FetchWord::RELATION_NONE);
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

    public function card(string $word)
    {
        // afficher un mot
        $elasticNode = ElasticNode::search(["match" => ['name' => $word]], 1)->getResults();
        if ($elasticNode === null) {
            $response = FetchWord::fetch(utf8_decode($word), -1);
            $parsed = WordParser::parse($response);

            if ($parsed->getCode() !== 404) {
                $rawNode = $parsed->getNode();

                $elasticNode = new ElasticNode();
                $elasticNode->setNode($rawNode);
                $elasticNode->save();
            }
        }

        return $elasticNode;
    }
}