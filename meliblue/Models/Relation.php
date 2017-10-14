<?php

namespace Meliblue\Models;


class Relation
{
    public $id;
    public $from;
    public $to;
    public $type;
    public $weight;

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
     * @param int|SimpleNode|null $from
     * @return Relation
     */
    public function setFrom(int $from): Relation
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @param int|SimpleNode|null $to
     * @return Relation
     */
    public function setTo(int $to): Relation
    {
        $this->to = $to;

        return $this;
    }

    /**
     * @param int $type
     * @return Relation
     */
    public function setType(int $type): Relation
    {
        $this->type = $type;

        return $this;
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


}