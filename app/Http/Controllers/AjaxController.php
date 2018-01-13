<?php
/**
 * Created by PhpStorm.
 * User: stardisblue
 * Date: 06/01/2018
 * Time: 19:06
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Meliblue\ElasticBlue\Models\ElasticNode;
use Meliblue\ElasticBlue\Models\ElasticNodeCache;
use Meliblue\ElasticBlue\Models\ElasticRelationIn;
use Meliblue\ElasticBlue\Models\ElasticRelationOut;
use Meliblue\FetchWord;
use Meliblue\Models\CleanNode;
use Meliblue\WordParser;

class AjaxController extends Controller
{
    public function card(Request $request)
    {
        $request->validate([
            'word' => 'required|string',
        ]);

        $word = $request->input('word');
        // afficher un mot
        $elasticNode = ElasticNode::getNode($word, 1);

        if ($elasticNode !== null) { // it's an array so it will be gladly converted to json
            return $elasticNode;
        }

        $response = FetchWord::fetchAsync($word, FetchWord::RELATION_NONE)->wait(true);
        $parsed = WordParser::parse($response);

        if ($parsed->getCode() !== 404) {
            $rawNode = $parsed->getNode();

            $createdNode = new ElasticNode();
            $createdNode->setNode($rawNode);
            $createdNode->save();

            return $createdNode;
        }

        return response("", Response::HTTP_NOT_FOUND); // yes :/
    }

    public function autocompleteNode(Request $request)
    {
        $request->validate([
            'q' => 'required|string',
        ]);

        $word = $request->input("q");

        $results = ElasticNode::autocomplete($word, 10);

        return $results === null ? response('', Response::HTTP_NOT_FOUND) : $results;
    }

    public function searchRelationInNode(Request $request, int $idNode)
    {
        $request->validate([
            'q' => 'required|string',
        ]);

        $word = $request->input('q');

        $relIn = ElasticRelationIn::nodeSearch($idNode, $word);
        $relOut = ElasticRelationOut::nodeSearch($idNode, $word);

        if ($relOut === null && $relIn === null) {
            return response('', Response::HTTP_NOT_FOUND);
        }

        $collection = collect($relIn['results']);
        $collectOut = collect($relOut['results']);

        $collection = AjaxController::tranformSearchRelations($collection, 'in')->toArray();
        $collectOut = AjaxController::tranformSearchRelations($collectOut, 'out')->toArray();


        foreach ($collectOut as $key => $value) {
            $collection[$key]['out'] = $value['out'];
        }

        return $collection;
    }

    /**
     * @param Collection $collection
     * @param string $prefix
     * @return Collection
     */
    private static function tranformSearchRelations(Collection $collection, string $prefix): Collection
    {
        return $collection->transform(function ($value) {
            return collect($value['inner_hits']['relationType']['hits']['hits'])
                ->transform(function ($type) {
                    return $type['_source'];
                })->toArray();
        })->keyBy(function ($item) {
            return $item['0']['idRelationType'];
        })->transform(function ($value) use ($prefix) {
            return [$prefix => $value];
        });
    }

    public function searchRelationInRelationType(Request $request, int $idNode, int $idRelationType, int $page = 0)
    {
        $request->validate([
            'q' => 'required|string',
        ]);

        $word = $request->input('q');

        $relIn = collect(ElasticRelationIn::nodeRelationTypeSearch($idNode, $idRelationType, $word, $page)['results']);
        $relOut =
            collect(ElasticRelationOut::nodeRelationTypeSearch($idNode, $idRelationType, $word, $page)['results']);

        $relIn->transform(function ($value) {
            $out = $value['_source'];
            $out['_score'] = $value['_score'];

            return $out;
        });
        $relOut->transform(function ($value) {
            $out = $value['_source'];
            $out['_score'] = $value['_score'];

            return $out;
        });

        return ['in' => $relIn, 'out' => $relOut];
    }

    public function getNodeRelation(Request $request, int $idNode, int $idRelationType, string $way, int $page = 0)
    {
        $request->validate([
            'orderBy' => 'filled|in:weight,name',
            'sort' => 'filled|in:asc,desc',
        ]);

        $orderBy = $request->input('orderBy', 'weight');
        $sortOrder = $request->input('sort', 'desc');

        if ($orderBy === "name") {
            $orderBy = "node.name";
        }

        if ($way === "in") {
            $relIn = ElasticRelationIn::pagination($idNode, $idRelationType, $page, $orderBy, $sortOrder);

            return $relIn === null ? response('', Response::HTTP_NOT_FOUND) : $relIn;
        } elseif ($way === "out") {
            $relOut = ElasticRelationOut::pagination($idNode, $idRelationType, $page, $orderBy, $sortOrder);

            return $relOut === null ? response('', Response::HTTP_NOT_FOUND) : $relOut;
        }
    }

    public function ajaxUpdateAndGet(int $idNode)
    {
        $nodeCache = ElasticNodeCache::get($idNode, ['name']);
        // mettre à jour le cache
        // recuperer la version mise à jour
        if ($nodeCache === null) {
            return response('', Response::HTTP_NOT_FOUND);
        }
        // we fetch it from jeuxdemot
        $response = FetchWord::fetch($nodeCache['name']);
        $parsed = WordParser::parse($response);

        // if the file is too big
        if ($parsed->getCode() === 413) {
            $response = FetchWord::fetch($nodeCache['name'], FetchWord::RELATION_NONE);
            $parsed = WordParser::parse($response);
        }

        if ($parsed->getCode() !== 404) {
            // we get the raw node
            $rawNode = $parsed->getNode();

            $cleanNode = new CleanNode($rawNode);
            // we extract the node information from it
            $elasticNode = new ElasticNode();
            $elasticNode->setNode($cleanNode);
            $elasticNode->save();

            ElasticRelationIn::deleteAll($cleanNode->id);
            ElasticRelationOut::deleteAll($cleanNode->id);

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

            return $nodeCache;
        }

        return response('', Response::HTTP_NOT_FOUND);
    }
}