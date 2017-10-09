<?php

namespace Meliblue;


class WordParser
{
    static function parse(\DOMElement $node): Node
    {
        $content = $node->textContent;
        $definition = $node->getElementsByTagName("def")->item(0)->textContent;
        $node = new Node();
        $node->setDefinition($definition);
        $separator = "\n";

        $line = strtok($content, $separator);
        while ($line !== false) {
            if ($line === null) {
                $line = strtok($separator);
            }

            $array = explode(';', $line);
            $type = $array[0];

            if ($type === "nt") {
                $nodeType = new NodeType();
                $nodeType->id = $array[1];
                $nodeType->name = $array[2];
                $node->addNodeType($nodeType);
            } elseif ($type === 'e') {
                $entity = new Entity();
                $entity->id = $array[1];
                $entity->name = $array[2];
                $entity->type = $array[3];
                $entity->weight = $array[4];
                $entity->formattedName = isset($array[5]) ? $array[5] : null;
                $node->addEntity($entity);
            } elseif ($type === 'rt') {
                $relationType = new RelationType();
                $relationType->id = $array[1];
                $relationType->code = $array[2];
                $relationType->name = $array[3];
                $relationType->description = $array[4];
                $node->addRelationType($relationType);
            } elseif ($type === 'r') {
                $relation = new Relation();
                $relation->id = $array[1];
                $relation->from = $array[2];
                $relation->to = $array[3];
                $relation->type = $array[4];
                $relation->weight = $array[5];
                $node->addRelation($relation);
            }
            $line = strtok($separator);
        }
        strtok('', '');

        return $node;
    }
}