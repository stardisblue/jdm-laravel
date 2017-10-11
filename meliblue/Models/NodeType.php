<?php

namespace Meliblue\Models;


use Meliblue\WordParser;

class NodeType
{
    public $name;

    /**
     * @param string $name
     */
    public function setName(string $name)
    {

        $this->name = WordParser::trim($name);
    }
}