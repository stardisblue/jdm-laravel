<?php

namespace Meliblue\Models;

class RawNode extends CardNode
{
    private $relationTypes = [];
    private $relations = [];
    private $nodeTypes = [];
    private $nodes = [];

    public function addNodeType(array $nodeType)
    {
        $this->nodeTypes[$nodeType['id']] = $nodeType;
    }

    public function addSimpleNode(SimpleNode $simpleNode)
    {
        $this->nodes[$simpleNode->id] = $simpleNode;
    }

    public function addRelationType(RelationType $relationType)
    {
        $this->relationTypes[$relationType->id] = $relationType;
    }

    public function addRelation(array $relation)
    {
        $this->relations[$relation['id']] = $relation;

    }


    /**
     * @return RelationType[]
     */
    public function getRelationTypes(): array
    {
        return $this->relationTypes;
    }

    /**
     * @param int $id
     * @return RelationType
     */
    public function getRelationType(int $id): RelationType
    {
        return $this->relationTypes[$id];
    }


    /**
     * @return array
     */
    public function getRelations(): array
    {
        return $this->relations;
    }

    public function setDescription(string $description): self
    {
        $this->description = trim(strip_tags($description));

        return $this;
    }

    /**
     *
     * @param int $id
     * @return SimpleNode
     */
    public function getNode(int $id): SimpleNode
    {
        return $this->nodes[$id];
    }

    /**
     * @return SimpleNode[]
     */
    public function getNodes(): array
    {
        return $this->nodes;
    }

    /**
     * @return array
     */
    public function getNodeTypes(): array
    {
        return $this->nodeTypes;
    }
}