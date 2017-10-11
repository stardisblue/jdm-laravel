<?php

namespace Meliblue\Models;


class Relation
{
    public $from;
    public $to;
    public $type;
    public $weight;


    /**
     * @param int|Entity|null $from
     */
    public function setFrom(int $from)
    {
        $this->from = $from;
    }

    /**
     * @param int|Entity|null $to
     */
    public function setTo(int $to)
    {
        $this->to = $to;
    }

    /**
     * @param int $type
     */
    public function setType(int $type)
    {
        $this->type = $type;
    }

    /**
     * @param int $weight
     */
    public function setWeight(int $weight)
    {
        $this->weight = $weight;
    }
}