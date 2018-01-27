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

    public static function nodeSearch(int $idNode, string $word): ?array
    {
        $paginationSize = config('elasticblue.pagination', 30);

        $params = [
            'index' => static::$index,
            'type' => static::$type,
            'body' => [
                "query" => [
                    "bool" => [
                        'must' => [
                            "multi_match" => [
                                'query' => $word,
                                'fields' => ['node.name.autocomplete', 'node.formattedName.autocomplete'],
                            ],
                        ],
                        'filter' => [
                            ['term' => ['idNode' => $idNode]],
                        ],
                    ],
                ],
                "collapse" => [
                    "field" => "idRelationType",
                    "inner_hits" => [
                        'name' => "relationType",
                        'size' => $paginationSize,
                    ],
                ],
            ],
            "filter_path" => ['hits.total', 'hits.hits._source', 'hits.hits.inner_hits'],
        ];

        $result = Es::search($params);

        if ($result['hits']['total'] === 0) {
            return null;
        }

        return ["total" => $result['hits']['total'], 'results' => $result['hits']['hits']];
    }

    public static function nodeRelationTypeSearch(int $idNode, int $idRelationType, string $word, int $page)
    {
        $paginationSize = config('elasticblue.pagination', 30);

        $params = [
            'index' => static::$index,
            'type' => static::$type,
            'body' => [
                'from' => $page * $paginationSize,
                'size' => $paginationSize,
                "query" => [
                    "bool" => [
                        'must' => [
                            "multi_match" => [
                                'query' => $word,
                                'fields' => ['node.name.autocomplete', 'node.formattedName.autocomplete'],
                            ],
                        ],
                        'filter' => [
                            ['term' => ['idNode' => $idNode]],
                            ['term' => ["idRelationType" => $idRelationType]],
                        ],
                    ],
                ],
            ],
            "filter_path" => ['hits.total', 'hits.hits._score', 'hits.hits._source'],
        ];

        $result = Es::search($params);

        if ($result['hits']['total'] <= $page * $paginationSize) { // intrusion check
            return null;
        }

        return ["count" => $result['hits']['total'], 'results' => $result['hits']['hits']];
    }

    /**
     * @param int $idNode
     * @param int $idRelationType
     * @param Relation[] $relations
     */
    public static function bulkCreate(int $idNode, int $idRelationType, array $relations): void
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

    public static function pagination(int $idNode, int $idRelationType, int $page, string $orderBy, string $sort):
    ?array
    {
        $paginationSize = config('elasticblue.pagination', 30);

        $sortOrder = [
            [$orderBy => ['order' => $sort]], // default sort :)
        ];
        if ($orderBy === 'node.name') {
            $sortOrder[] = ['weight' => ['order' => 'desc']];    // fallback value 1
            $sortOrder[] = 'id';                                 // fallback value 2
        } elseif ($orderBy === 'weight') {
            $sortOrder[] = 'id';                                 // fallback value : fuck it
        } else {
            $sortOrder[] = ['node.name' => ['order' => 'asc']];  // fallback value 1
            $sortOrder[] = ['weight' => ['order' => 'desc']];    // fallback value 2
            $sortOrder[] = 'id';                                 // fallback value 3
        }

        $params = [
            'index' => static::$index,
            'type' => static::$type,
            'body' => [
                'from' => $page * $paginationSize,
                'size' => $paginationSize,
                'sort' => $sortOrder,
                "query" => [
                    "bool" => [
                        'filter' => [
                            ['term' => ['idNode' => $idNode]],
                            ['term' => ["idRelationType" => $idRelationType]],
                        ],
                    ],
                ],
            ],
            "filter_path" => ['hits.total', 'hits.hits._source'],
        ];

        $result = Es::search($params);

        if ($result['hits']['total'] <= $page * $paginationSize) { // intrusion check
            return null;
        }

        return [
            "count" => $result['hits']['total'],
            'results' => collect($result['hits']['hits'])->transform(function ($value) {
                return $value['_source'];
            })->toArray(),
        ];
    }

    public
    static function deleteAll(
        int $idNode
    )
    {
        $params = [
            'index' => static::$index,
            'type' => static::$type,
            'body' => ["query" => ["term" => ["idNode" => $idNode]]],
        ];

        Es::deleteByQuery($params);

    }

    public
    function addRelation(
        $idNode,
        array $relation
    )
    {
        $this->id = $relation['id'];
        $this->idNode = $idNode;
        $this->idRelationType = $relation['id'];
        $this->weight = $relation['weight'];
    }

    public
    function addNode(
        SimpleNode $node
    )
    {
        $this->node = $node;
    }
}
