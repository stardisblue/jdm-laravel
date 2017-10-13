<?php
/**
 * Created by PhpStorm.
 * User: stardisblue
 * Date: 13/10/2017
 * Time: 17:02
 */

namespace Meliblue;


class Node
{
    public $id;
    public $name;
    public $formattedName;
    public $description;
    public $nodeType;
    public $weight;
    public $relationTypes = [];


    public function __construct(RawNode $rawNode)
    {
        foreach ($rawNode->relations as $id => $rawRelation) {
            $relation = new \stdClass();
            $relation->id = $id;
            $relation->weight = $rawRelation->weight;

            // TODO
            if ($rawRelation->from !== $rawNode->id && isset($rawNode->nodes[$rawRelation->from])) {
                $relation->node = $rawNode->nodes[$rawRelation->from];
            } elseif ($rawRelation->to !== $rawNode->id && isset($rawNode->nodes[$rawRelation->to])) {
                $relation->node = $rawNode->nodes[$rawRelation->to];
            } else {
                $relation->node = null;
            }

            $this->relationTypes[] = $relation;
        }

        foreach ($rawNode->relations as $id => $rawRelation) {
            if ($rawRelation->from !== $rawNode->id && isset($rawNode->nodes[$rawRelation->from])) {
                $rawRelation->from = $rawNode->nodes[$rawRelation->from];
            } else {
                $rawRelation->from = null;
            }

            if ($rawRelation->to !== $rawNode->id && isset($rawNode->nodes[$rawRelation->to])) {
                $rawRelation->to = $rawNode->nodes[$rawRelation->to];
            } else {
                $rawRelation->to = null;
            }
            $rawNode->relationTypes[$rawRelation->type]->relations[] = $rawRelation;
            unset($rawRelation->type);
        }

        unset($rawNode->relations);
        unset($rawNode->nodes);
    }
}