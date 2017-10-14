<?php

namespace Meliblue\Models;


use Meliblue\WordParser;

class NodeType
{
    public $id;
    public $name;

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id): NodeType
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): NodeType
    {

        $this->name = WordParser::trim($name);

        return $this;
    }
}