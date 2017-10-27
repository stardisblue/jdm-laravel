<?php namespace App;

use Meliblue\ElasticBlue\ElasticBlueModel;

class Node extends ElasticBlueModel
{
    protected static $index = 'nodes';
    protected static $type = 'node';

    public $id;
    public $name;
    public $description;
    public $formattedName;
    public $weight;
    public $nodeType;

    public function setNode(\Meliblue\Node $node)
    {
        $this->id = $node->id;
        $this->name = $node->name;
        $this->description = $node->description;
        $this->formattedName = $node->formattedName;
        $this->weight = $node->weight;
        $this->nodeType = $node->nodeType;
    }
}