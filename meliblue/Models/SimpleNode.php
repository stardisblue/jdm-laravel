<?php

namespace Meliblue\Models;


use Meliblue\WordParser;

class SimpleNode
{
    public $id;
    public $name;
    public $formattedName;
    public $nodeType;
    public $weight;

    public function setNode(SimpleNode $node): void
    {
        $this->setId($node->id)
            ->setName($node->name)
            ->setFormattedName($node->formattedName)
            ->setNodeType($node->nodeType)
            ->setWeight($node->weight);
    }

    /**
     * @param int $weight
     * @return SimpleNode
     */
    public function setWeight(int $weight): SimpleNode
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * @param int $nodeType
     * @return SimpleNode
     */
    public function setNodeType(int $nodeType): SimpleNode
    {
        $this->nodeType = $nodeType;

        return $this;
    }

    /**
     * @param string|null $formattedName
     * @return SimpleNode
     */
    public function setFormattedName($formattedName): SimpleNode
    {
        if ($formattedName !== null) {
            $this->formattedName = WordParser::trim($formattedName);
        } else {
            $this->formattedName = null;
        }

        return $this;
    }

    /**
     * @param string $name
     * @return SimpleNode
     */
    public function setName(string $name): SimpleNode
    {
        $this->name = WordParser::trim($name);

        return $this;
    }

    /**
     * @param int $id
     * @return SimpleNode
     */
    public function setId(int $id): SimpleNode
    {
        $this->id = $id;

        return $this;
    }
}