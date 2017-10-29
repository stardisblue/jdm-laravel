<?php

namespace Meliblue\ElasticBlue;

class ElasticBlueResult
{
    private $results = null;

    public function __construct(array $result)
    {
        if (sizeof($result['hits']['hits']) === 1) {
            $this->results = $result['hits']['hits'][0];
        } elseif (sizeof($result['hits']['hits']) > 1) {
            $this->results = $result['hits']['hits'];
        }
    }

    /**
     * @return array
     */
    public function getResults()
    {
        return $this->results['_source'];
    }

}