<?php namespace Meliblue\Models;


class Relation
{

    public $id;
    public $weight;
    public $node;


    public function __construct(array $relation)
    {
        $this->setId($relation['id'])
            ->setWeight($relation['weight']);
    }

    /**
     * @param int $weight
     * @return Relation
     */
    public function setWeight(int $weight): Relation
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * @param int $id
     * @return Relation
     */
    public function setId(int $id): Relation
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param SimpleNode $node
     */
    public function setNode(SimpleNode $node)
    {
        $this->node = $node;
    }
}