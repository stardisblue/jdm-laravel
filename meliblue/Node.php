<?php
/**
 * Created by PhpStorm.
 * User: stardisblue
 * Date: 13/10/2017
 * Time: 17:02
 */

namespace Meliblue;


use Meliblue\Models\Relation;
use Meliblue\Models\SimpleNode;

class Node extends SimpleNode
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
     * @return Node
     */
    public function setDescription(string $description): Node
    {
        $this->description = $description;

        return $this;
    }


    private function setRelationTypes(array $relationTypes)
    {
        $this->relationTypes = array_values($relationTypes);
    }

    private function setNodeTypes(array $nodeTypes)
    {
        $this->nodeTypes = array_values($nodeTypes);
    }
}