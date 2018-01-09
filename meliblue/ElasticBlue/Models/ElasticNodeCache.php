<?php namespace Meliblue\ElasticBlue\Models;


use Meliblue\Models\CleanNode;

class ElasticNodeCache extends ElasticNode
{

    protected static $index = 'nodes-cache';
    protected static $type = "node-cache";

    public $timestamp;
    public $relationTypes = [];
    public $nodeTypes = [];

    /**
     * Sets and trims the relations
     *
     * @param CleanNode $node
     * @return $this
     */
    public function setNode($node)
    {
        parent::setNode($node);

        $defaultsize = config('elasticblue.pagination');

        foreach ($node->relationTypes as $rel) {

            $rel->relations['in'] = array_slice($rel->relations['in'], 0, $defaultsize);
            $rel->relations['out'] = array_slice($rel->relations['out'], 0, $defaultsize);
        }
        $this->relationTypes = $node->relationTypes;
        $this->nodeTypes = $node->nodeTypes;

        $this->timestamp = now()->toIso8601String();

        return $this;
    }
}