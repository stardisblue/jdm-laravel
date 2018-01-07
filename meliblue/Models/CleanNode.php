<?php namespace Meliblue\Models;


class CleanNode extends CardNode
{
    public $description;
    public $relationTypes = [];
    public $nodeTypes = [];

    public function __construct(RawNode $rawNode)
    {
        $this->setNode($rawNode);

        $this->setDescription($rawNode->description);

        foreach ($rawNode->getRelations() as $relation) {
            $relationType = $rawNode->getRelationType($relation['type']);
            $newRelation = new Relation($relation);

            if ($this->id === $relation['from'] && $this->id === $relation['to']) {// the relation is pointing himself
                $newRelation->setNode($rawNode->getNode($this->id));

                $relationType->addRelationIn($newRelation);
                $relationType->addRelationOut($newRelation);
            } elseif ($this->id === $relation['to']) {// the node is pointed at from another node
                $newRelation->setNode($rawNode->getNode($relation['from']));

                $relationType->addRelationIn($newRelation);
            } elseif ($this->id === $relation['from']) {// the node is pointing to another node
                $newRelation->setNode($rawNode->getNode($relation['to']));

                $relationType->addRelationOut($newRelation);
            }
        }

        $this->setRelationTypes($rawNode->getRelationTypes());
        $this->setNodeTypes($rawNode->getNodeTypes());
    }

    /**
     * @param string $description
     * @return CleanNode
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param RelationType[] $relationTypes
     */
    private function setRelationTypes(array $relationTypes)
    {
        foreach ($relationTypes as $relationType) {
            $relationType->sortRelationsByWeight();
        }

        $this->relationTypes = array_values($relationTypes);
    }

    private function setNodeTypes(array $nodeTypes)
    {
        $this->nodeTypes = array_values($nodeTypes);
    }
}