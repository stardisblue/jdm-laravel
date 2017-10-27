<?php namespace App;

class NodeCache extends Node
{

    protected static $index = 'nodes-cache';
    protected static $type = "node-cache";
    private static $max_relations = 30;
    public $relationTypes = [];
    public $nodeTypes = [];

    public function setNode(\Meliblue\Node $node)
    {
        parent::setNode($node);
        $this->nodeTypes = $node->nodeTypes;


        foreach ($node->relationTypes as $relationType) {
            $relationTypeCopy = [];
            $relationTypeCopy['id'] = $relationType->id;
            $relationTypeCopy['code'] = $relationType->code;
            $relationTypeCopy['name'] = $relationType->name;
            $relationTypeCopy['description'] = $relationType->description;
            $relationTypeCopy['relations']['in'] = array_slice($relationType->relations['in'], 0,
                self::$max_relations);
            $relationTypeCopy['relations']['out'] = array_slice($relationType->relations['out'], 0,
                self::$max_relations);
            $this->relationTypes[] = $relationTypeCopy;
        }
    }
}