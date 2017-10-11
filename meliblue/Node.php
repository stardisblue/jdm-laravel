<?php

namespace Meliblue;


use Meliblue\Models\Entity;
use Meliblue\Models\NodeType;
use Meliblue\Models\Relation;
use Meliblue\Models\RelationType;

class Node
{
    public $id;
    public $name;
    public $definition;
    public $type;
    public $weight;
    public $formattedName;
    public $relationTypes = [];
    public $relations = [];
    public $nodeTypes = [];
    public $nodes = [];


    public function addNodeType(int $id, NodeType $nodeType)
    {
        $this->nodeTypes[$id] = $nodeType;
    }

    public function addEntity(int $id, Entity $entity)
    {
        $this->nodes[$id] = $entity;
    }

    public function addRelationType(int $id, RelationType $relationType)
    {
        $this->relationTypes[$id] = $relationType;
    }

    public function addRelation(int $id, Relation $relation)
    {
        $this->relations[$id] = $relation;
    }

    public function setDefinition(String $definition)
    {
        $this->definition = trim($definition);
    }

    public function setNode(int $id, Entity $entity)
    {
        $this->id = $id;
        $this->name = $entity->name;
        $this->type = $entity->type;
        $this->weight = $entity->weight;
        $this->formattedName = $entity->formattedName;
    }

    public function prepare()
    {
        foreach ($this->relations as $id => $relation) {
            $relation->from = ($relation->from === $this->id) ? null : $this->nodes[$relation->from];
            $relation->to = ($relation->to === $this->id) ? null : $this->nodes[$relation->to];
            $this->relationTypes[$relation->type]->addRelation($id, $relation);
        }

        unset($this->relations);
        unset($this->nodes);
    }
}