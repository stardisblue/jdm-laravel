<?php

namespace Meliblue;


use Meliblue\Models\Entity;
use Meliblue\Models\NodeType;
use Meliblue\Models\Relation;
use Meliblue\Models\RelationType;
use Psr\Http\Message\ResponseInterface;

class WordParser
{

    static function parse(ResponseInterface $response): WordWrapper
    {
        $wrapper = new WordWrapper();

        $contents = $response->getBody()->getContents();


        $domDoc = new \DOMDocument('1.0', 'ISO-8859-1');
        @$domDoc->loadHTML($contents);

        $content = $domDoc->getElementsByTagName('code')->item(0);

        if ($content === null) {
            $wrapper->setCode(404);
            $wrapper->setReason("word does not exist");
        } elseif ($domDoc->getElementsByTagName("warning")->length > 0) {
            $wrapper->setCode(413);
            $wrapper->setReason("TOOBIG_USE_DUMP");
        } else {
            $wrapper->setNode(WordParser::extract($content));
            $wrapper->setCode($response->getStatusCode());
            $wrapper->setReason($response->getReasonPhrase());
        }

        return $wrapper;
    }

    /**
     * @param \DOMElement $DOMElement
     * @return RawNode
     */
    static function extract(\DOMElement $DOMElement): RawNode
    {
        $content = $DOMElement->textContent;
        $definition = $DOMElement->getElementsByTagName("def")->item(0)->textContent;

        $node = new RawNode();
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
                $nodeType->setName($array[2]);
                $node->addNodeType($array[1], $nodeType);
            } elseif ($type === 'e') {
                $entity = new Entity();

                if (filter_var($array[3], FILTER_VALIDATE_INT) === false) {
                    $array[2] = $array[2] . ';' . array_splice($array, 3, 1)[0];
                }

                $entity->setName($array[2]);
                $entity->setType($array[3]);
                $entity->setWeight($array[4]);
                isset($array[5]) ? $entity->setFormattedName($array[5]) : null;
                $node->addEntity($array[1], $entity);
            } elseif ($type === 'rt') {
                $relationType = new RelationType();
                $relationType->setCode($array[2]);
                $relationType->setName($array[3]);
                $relationType->setDescription($array[4]);
                $node->addRelationType($array[1], $relationType);
            } elseif ($type === 'r') {
                $relation = new Relation();
                $relation->setFrom($array[2]);
                $relation->setTo($array[3]);
                $relation->setType($array[4]);
                $relation->setWeight($array[5]);
                $node->addRelation($array[1], $relation);
            }
            $line = strtok($separator);
        }
        strtok('', '');

        foreach ($node->nodes as $id => $entity) {
            $node->setNode($id, $entity);
            break;
        }

        return $node;
    }


    static function trim(string $word, string $separator = '\''): string
    {
        $len = strlen($word);
        if ($word[0] === $separator) {
            $iniidx = 1;
        } else {
            $iniidx = 0;
        }

        if ($word[$len - 1] === $separator) {
            $endidx = -1;
        } else {
            $endidx = $len - 1;
        }

        if ($iniidx == 1 || $endidx == -1) {
            return substr($word, $iniidx, $endidx);
        }

        return $word;
    }
}