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
            if ($relation->from !== $this->id && isset($this->nodes[$relation->from])) {
                $relation->from = $this->nodes[$relation->from];
            } else {
                $relation->from = null;
            }

            if ($relation->to !== $this->id && isset($this->nodes[$relation->to])) {
                $relation->to = $this->nodes[$relation->to];
            } else {
                $relation->to = null;
            }
            $this->relationTypes[$relation->type]->relations[] = $relation;
            unset($relation->type);
        }

        unset($this->relations);
        unset($this->nodes);
    }
}