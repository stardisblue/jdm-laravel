<?php
/**
 * Created by PhpStorm.
 * User: stardisblue
 * Date: 13/10/2017
 * Time: 21:41
 */

namespace Meliblue\Models;


class JsonRelation
{

    public $id;
    public $weight;
    public $node;


    public function __construct(Relation $relation)
    {
        $this->setId($relation->id)
            ->setWeight($relation->weight);
    }

    /**
     * @param int $weight
     * @return JsonRelation
     */
    public function setWeight(int $weight): JsonRelation
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * @param int $id
     * @return JsonRelation
     */
    public function setId(int $id): JsonRelation
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