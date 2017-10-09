<?php
/**
 * Created by PhpStorm.
 * User: stardisblue
 * Date: 09/10/2017
 * Time: 18:55
 */

namespace Meliblue;


class Node
{
    public $name;
    public $definition;
    public $relationTypes = [];
    public $nodeTypes = [];
    public $nodes = [];


    public function addNodeType(NodeType $nodeType)
    {
        $this->nodeTypes[$nodeType->id] = $nodeType;
    }

    public function addEntity(Entity $entity)
    {
        $this->nodes[$entity->id] = $entity;
    }

    public function addRelationType(RelationType $relationType)
    {
        $this->relationTypes[$relationType->id] = $relationType;
    }

    public function addRelation(Relation $relation)
    {
        $this->relationTypes[$relation->type]->relations[$relation->id] = $relation;
    }

    public function setDefinition(String $definition)
    {
        $this->definition = $definition;
    }

    public function setName(String $name)
    {
        $this->name = $name;
    }
}