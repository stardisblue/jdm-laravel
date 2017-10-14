<?php

namespace Meliblue;

use Meliblue\Models\NodeType;
use Meliblue\Models\Relation;
use Meliblue\Models\RelationType;
use Meliblue\Models\SimpleNode;

class RawNode extends SimpleNode
{
    public $description;
    private $relationTypes = [];
    private $relations = [];
    private $nodeTypes = [];
    private $nodes = [];

    public function addNodeType(NodeType $nodeType)
    {
        $this->nodeTypes[$nodeType->id] = $nodeType;
    }

    public function addSimpleNode(SimpleNode $simpleNode)
    {
        $this->nodes[$simpleNode->id] = $simpleNode;
    }

    public function addRelationType(RelationType $relationType)
    {
        $this->relationTypes[$relationType->id] = $relationType;
    }

    public function addRelation(Relation $relation)
    {
        $this->relations[$relation->id] = $relation;

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
     * @return Relation[]
     */
    public function getRelations(): array
    {
        return $this->relations;
    }

    public function setDescription(string $description)
    {
        $this->description = trim($description);
    }

    public function setNode(SimpleNode $node)
    {
        $this->setId($node->id)
            ->setName($node->name)
            ->setFormattedName($node->formattedName)
            ->setNodeType($node->nodeType)
            ->setWeight($node->weight);
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
     * @return NodeType[]
     */
    public function getNodeTypes(): array
    {
        return $this->nodeTypes;
    }
}