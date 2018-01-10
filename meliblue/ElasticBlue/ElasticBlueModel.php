<?php

namespace Meliblue\ElasticBlue;

use Elasticsearch\Common\Exceptions\Missing404Exception;
use Meliblue\ElasticBlue\Facade\Es;

class ElasticBlueModel
{

    protected static $index = '';
    protected static $type = '';
    protected static $primaryKey = 'id';

    public static function search($query, $size = null, $type = null, $index = null)
    {
        $search = [];
        $search['index'] = $index === null ? static::$index : $index;
        $search['type'] = $type === null ? static::$type : $type;
        $search['body']['query'] = $query;

        if ($size !== null) {
            $search['body']['size'] = $size;
        }

        return new ElasticBlueResult(Es::search($search));
    }

    /**
     * @param $id
     * @param array $fields
     * @return array|null
     */
    public static function get($id, array $fields = []): ?array
    {
        $params = [
            'index' => static::$index,
            'type' => static::$type,
            'id' => $id,
            '_source' => $fields,
        ];

        try {
            $result = Es::get($params);

        } catch (Missing404Exception $e) {
            return null;
        }

        return $result['_source'];
    }

    public static function delete($id)
    {
        $params = [
            'index' => static::$index,
            'type' => static::$type,
            'id' => $id,
        ];

        return Es::delete($params);
    }

    public function save()
    {
        $params = [
            'index' => static::$index,
            'type' => static::$type,
            'id' => $this->{static::$primaryKey},
            'body' => $this,
        ];

        return Es::index($params);
    }
}