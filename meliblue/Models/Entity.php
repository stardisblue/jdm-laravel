<?php

namespace Meliblue\Models;


use Meliblue\WordParser;

class Entity
{
    public $name;
    public $type;
    public $weight;
    public $formattedName;

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = WordParser::trim($name);
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

    /**
     * @param string|null $formattedName
     */
    public function setFormattedName($formattedName)
    {
        $this->formattedName = WordParser::trim($formattedName) ;
    }
}