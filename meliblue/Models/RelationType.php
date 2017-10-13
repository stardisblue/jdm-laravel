<?php

namespace Meliblue\Models;


use Meliblue\WordParser;

class RelationType
{
    public $code;
    public $name;
    public $description;

    /**
     * @param string $code
     */
    public function setCode(string $code)
    {
        $this->code = WordParser::trim($code);
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = WordParser::trim($name);
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }
}