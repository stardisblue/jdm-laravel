<?php namespace Meliblue\ElasticBlue\Models;

use Illuminate\Contracts\Support\Jsonable;
use Meliblue\ElasticBlue\ElasticBlueModel;
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