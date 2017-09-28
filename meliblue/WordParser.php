<?php
/**
 * Created by PhpStorm.
 * User: MELY
 * Date: 9/28/2017
 * Time: 3:45 PM
 */

namespace Meliblue;


class WordParser
{
    static function parse(\DOMElement $node): \stdClass
    {
        $string = $node->textContent;
        $definition = $node->getElementsByTagName("def")->item(0)->textContent;
        $object = new \stdClass();
        $object->definition = $definition;
        $object->nodeTypes = [];
        $object->entities = [];
        $object->relationTypes = [];
        $object->relations = [];
        $separator = "\n";

        $line = strtok($string, $separator);
        while ($line !== false) {
            if ($line === null) {
                $line = strtok($separator);
            }

            $array = explode(';', $line);
            $type = $array[0];
            if ($type === "nt") {
                $nodeType = new \stdClass();
                $nodeType->id = $array[1];
                $nodeType->name = $array[2];
                $object->nodeTypes[] = $nodeType;
            } elseif ($type === 'e') {
                $entity = new \stdClass();
                $entity->id = $array[1];
                $entity->name = $array[2];
                $entity->type = $array[3];
                $entity->weight = $array[4];
                $entity->formattedName = isset($array[5]) ? $array[5] : null;
                $object->entities[] = $entity;
            } elseif ($type === 'rt') {
                $relationType = new \stdClass();
                $relationType->id = $array[1];
                $relationType->code = $array[2];
                $relationType->name = $array[3];
                $relationType->description = $array[4];
                $object->relationTypes[] = $relationType;
            } elseif ($type === 'r') {
                $relation = new \stdClass();
                $relation->id = $array[1];
                $relation->from = $array[2];
                $relation->to = $array[3];
                $relation->type = $array[4];
                $relation->weight = $array[5];
                $object->relations[] = $relation;
            }
            $line = strtok($separator);
        }

        return $object;
    }
}