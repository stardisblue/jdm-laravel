<?php

namespace Meliblue\Models;


use Meliblue\WordParser;

class RelationType
{
    public $id;
    public $code;
    public $name;
    public $description;
    public $relations = ['in' => [], 'out' => []];

    /**
     * @param int $id
     * @return RelationType
     */
    public function setId(int $id): RelationType
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param string $code
     * @return RelationType
     */
    public function setCode(string $code): RelationType
    {
        $this->code = WordParser::trim($code);

        return $this;
    }

    /**
     * @param string $name
     * @return RelationType
     */
    public function setName(string $name): RelationType
    {
        $this->name = WordParser::trim($name);

        return $this;
    }

    /**
     * @param string $description
     * @return RelationType
     */
    public function setDescription(string $description): RelationType
    {
        $this->description = $description;

        return $this;
    }

    public function addRelationIn(Relation $relation)
    {
        $this->relations['in'][] = $relation;
    }

    public function addRelationOut(Relation $relation)
    {
        $this->relations['out'][] = $relation;
    }

    public function sortRelationsByWeight()
    {

        $func = function ($first, $second) {
            $weight = $second->weight - $first->weight;

            return $weight !== 0 ? $weight : $second->id - $first->id;
        };

        usort($this->relations['in'], $func);

        usort($this->relations['out'], $func);
    }
}

