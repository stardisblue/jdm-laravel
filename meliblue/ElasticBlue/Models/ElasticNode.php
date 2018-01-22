<?php namespace Meliblue\ElasticBlue\Models;

use Illuminate\Contracts\Support\Jsonable;
use Meliblue\ElasticBlue\ElasticBlueModel;
use Meliblue\ElasticBlue\Facade\Es;
use Meliblue\Models\CardNode;

class ElasticNode extends ElasticBlueModel implements Jsonable
{
    protected static $index = 'nodes';
    protected static $type = 'node';

    public $id;
    public $name;
    public $description;
    public $formattedName;
    public $weight;
    public $nodeType;

    public static function nodeSearch($query, $page = 0)
    {
        $pagination = config('elasticblue.pagination', 30);

        $params = [
            'index' => static::$index,
            'type' => static::$type,
            'body' => [
                "query" => [
                    "simple_query_string" => [
                        "fields" => ["name", "formattedName"],
                        "query" => $query,
                        "flags" => "PREFIX|PHRASE",
                    ],
                ],
                "from" => $page * $pagination,
                "size" => $pagination,
            ],
            "filter_path" => ['hits.total', 'hits.hits._source'],
        ];

        $result = Es::search($params);


        if ($result['hits']['total'] === 0 || $result['hits']['total'] <= $page * $pagination) { // intrusion check
            return null;
        }

        return collect($result['hits']['hits'])->transform(function ($value) {
            return $value['_source'];
        })->toArray();
    }

    public static function nodeRegexpSearch($query, $page = 0)
    {
        $pagination = config('elasticblue.pagination', 30);

        $params = [
            'index' => static::$index,
            'type' => static::$type,
            'body' => [
                "query" => [
                    "regexp" => [
                        "name" => $query,
                    ],
                ],
                "from" => $page * $pagination,
                "size" => $pagination,
            ],
            "filter_path" => ['hits.total', 'hits.hits._source'],
        ];
        try {
            $result = Es::search($params);
        } catch (\Exception $e) {
            return null;
        }


        if ($result['hits']['total'] === 0 || $result['hits']['total'] <= $page * $pagination) { // intrusion check
            return null;
        }

        return collect($result['hits']['hits'])->transform(function ($value) {
            return $value['_source'];
        })->toArray();
    }

    public static function autocomplete(string $word, int $size = 10)
    {
        $params = [
            'index' => static::$index,
            'type' => static::$type,
            'body' => [
                "query" => [
                    "multi_match" => [
                        'query' => $word,
                        'fields' => ['name.autocomplete', 'formattedName.autocomplete'],
                    ],
                ],
                "size" => $size,
            ],
            "_source" => ["name", "formattedName"],
            "filter_path" => ['hits.total', 'hits.hits._source'],
        ];

        $result = Es::search($params);

        if ($result['hits']['total'] === 0) { // intrusion check
            return null;
        }

        return [
            "total" => $result['hits']['total'],
            'results' => collect($result['hits']['hits'])->transform(function ($value) {
                return $value['_source'];
            })->toArray(),
        ];
    }

    public static function getNode(string $word, int $size = null, $type = null, $index = null)
    {
        return static::search([
            "bool" => [
                "should" => [
                    ["term" => ['name' => $word]],
                    ["term" => ['formattedName' => $word]],
                ],
            ],
        ], $size, $type, $index);


    }

    /**
     * @param CardNode $node
     */
    public function setNode($node)
    {
        $this->id = $node->id;
        $this->name = $node->name;
        $this->description = $node->description;
        $this->formattedName = $node->formattedName;
        $this->weight = $node->weight;
        $this->nodeType = $node->nodeType;
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this);
    }
}