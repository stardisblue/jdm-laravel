<?php
/**
 * Created by PhpStorm.
 * User: stardisblue
 * Date: 06/01/2018
 * Time: 19:06
 */

namespace App\Http\Controllers;


use Meliblue\ElasticBlue\Models\ElasticNode;
use Meliblue\ElasticBlue\Models\ElasticNodeCache;
use Meliblue\ElasticBlue\Models\ElasticRelationIn;
use Meliblue\ElasticBlue\Models\ElasticRelationOut;
use Meliblue\FetchWord;
use Meliblue\Models\CleanNode;
use Meliblue\WordParser;

class AjaxController extends Controller
{
    public function card(string $word)
    {
        // afficher un mot
        //$elasticNode = ElasticNode::search(["term" => ['name' => $word]], 1)->getResults();
        $elasticNode = null;
        if ($elasticNode !== null) { // it's an array so it will be gladly converted to json
            return $elasticNode;
        }

        $response = FetchWord::fetch(utf8_decode($word), -1);
        $parsed = WordParser::parse($response);

        if ($parsed->getCode() !== 404) {
            $rawNode = $parsed->getNode();

            $createdNode = new ElasticNode();
            $createdNode->setNode($rawNode);
            $createdNode->save();

            return $createdNode;

        }

        return $elasticNode; // yes :/
    }


    public function ajaxUpdateAndGet(string $word)
    {
        $nodeCache = ElasticNodeCache::search(["term" => ['name' => $word]], 1)->getResults();
        // mettre à jour le cache
        // recuperer la version mise à jour
        if ($nodeCache === null) {
            return $nodeCache;
        }
        // we fetch it from jeuxdemot
        $response = FetchWord::fetch(utf8_decode($word));
        // we extract the content
        $parsed = WordParser::parse($response);
        $reason = $parsed->getReason();

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

        return null;

    }
}