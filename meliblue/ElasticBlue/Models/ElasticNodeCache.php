<?php namespace Meliblue\ElasticBlue\Models;


use Meliblue\Models\CleanNode;
use PhpParser\Node;

class ElasticNodeCache extends ElasticNode
{

    protected static $index = 'nodes-cache';
    protected static $type = "node-cache";
    private static $maxRelations = 30;
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

        foreach ($node->relationTypes as $rel) {
            $rel->relations['in'] = array_slice($rel->relations['in'], 0, static::$maxRelations);
            $rel->relations['out'] = array_slice($rel->relations['out'], 0, static::$maxRelations);
        }
        $this->relationTypes = $node->relationTypes;
        $this->nodeTypes = $node->nodeTypes;

        return $this;
    }
}