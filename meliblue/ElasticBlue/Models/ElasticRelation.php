<?php

namespace Meliblue\ElasticBlue\Models;

use Meliblue\ElasticBlue\ElasticBlueModel;
use Meliblue\ElasticBlue\Facade\Es;
use Meliblue\Models\Relation;
use Meliblue\Models\SimpleNode;

class ElasticRelation extends ElasticBlueModel
{
    protected static $index = 'relations';

    public $id;
    public $idNode;
    public $idRelationType;
    public $weight;
    public $node;

    /**
     * @param int $idNode
     * @param int $idRelationType
     * @param Relation[] $relations
     */
    public static function bulkCreate(int $idNode, int $idRelationType, array $relations)
    {
        if (sizeof($relations) > 0) {
            $params = ['body' => []];

            foreach ($relations as $rel) {
                $params['body'][] = [
                    'create' => [
                        '_index' => static::$index,
                        '_type' => static::$type,
                        '_id' => $rel->id,
                    ],
                ];

                $elasticRelation = new ElasticRelation();
                $elasticRelation->id = $rel->id;
                $elasticRelation->idNode = $idNode;
                $elasticRelation->idRelationType = $idRelationType;
                $elasticRelation->weight = $rel->weight;
                $elasticRelation->node = $rel->node;


                $params['body'][] = $elasticRelation;
            }

            Es::bulk($params);
        }

    }

    public static function deleteAll(int $idNode)
    {
        $params = [
            'index' => static::$index,
            'type' => static::$type,
            'body' => ["query" => ["term" => ["idNode" => $idNode]]],
        ];

        Es::deleteByQuery($params);

    }

    public function addRelation($idNode, array $relation)
    {
        $this->id = $relation['id'];
        $this->idNode = $idNode;
        $this->idRelationType = $relation['id'];
        $this->weight = $relation['weight'];
    }

    public function addNode(SimpleNode $node)
    {
        $this->node = $node;
    }
}
