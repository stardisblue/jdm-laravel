<?php namespace Meliblue;


use Meliblue\Models\RawNode;
use Meliblue\Models\RelationType;
use Meliblue\Models\SimpleNode;
use Psr\Http\Message\ResponseInterface;

class WordParser
{

    static function parse(ResponseInterface $response): WordWrapper
    {
        $wrapper = new WordWrapper();
        $content = utf8_encode($response->getBody()->getContents());

        $separator = "\n";
        $line = strtok($content, $separator);

        // getting to the code tag
        // are we in code yet ?
        while ($line !== false && $line !== "<CODE>") {// fail safe check
            $line = strtok($separator);
        }

        if ($line === false) { // no content
            return $wrapper->setCode(404)
                ->setReason("NOT_EXIST");
        }

        // YES !! we are in code
        // getting the description and the error if has some
        $error = null;
        $description = "";
        $areWeInDefYet = false;
        $isDefDoneYet = false; // ??
        while ($line !== false && !$isDefDoneYet) {
            if (!$areWeInDefYet) {
                if ("<WARNING>" === substr($line, 0, 9)) {
                    $error = "TOOBIG_USE_DUMP";
                } elseif ("<def>" === $line) {
                    $areWeInDefYet = true;
                }

                $line = strtok($separator);
                continue;
            }

            if ($line === "</def>") {
                $isDefDoneYet = true;
            } else {
                $description .= trim(strip_tags($line))."\n";
            }

            $line = strtok($separator);
        }

        // yes def is done
        $node = new RawNode();
        $node->setDescription($description);


        while ($line !== false) {
            if ($line === null) {
                $line = strtok($separator);
                continue;
            }

            $array = explode(';', $line);
            $type = $array[0];

            if ($type === 'nt') {
                $node->addNodeType([
                    'id' => (int)$array[1],
                    'name' => WordParser::trim($array[2]),
                ]);
            } elseif ($type === 'e') {
                $index = 2;
                while (substr($array[$index], -1, 1) !== "'") {
                    $index++;
                }
                if ($index !== 2) {
                    $array[2] .= ";".implode(';', array_splice($array, 3, $index - 2));
                }

                $entity = new SimpleNode();
                $entity->setId($array[1])
                    ->setName($array[2])
                    ->setNodeType($array[3])
                    ->setWeight($array[4])
                    ->setFormattedName(isset($array[5]) ? $array[5] : null);

                $node->addSimpleNode($entity);
            } elseif ($type === 'rt') {
                if($array[1] === ""){
                    continue; // lol ptdr c'est quoi ca ! https://github.com/stardisblue/jdm-laravel/issues/19
                }
                $relationType = new RelationType();
                $relationType->setId($array[1])
                    ->setCode($array[2])
                    ->setName($array[3])
                    ->setDescription($array[4]);

                $node->addRelationType($relationType);
            } elseif ($type === 'r') {
                $node->addRelation([
                    'id' => (int)$array[1],
                    'from' => (int)$array[2],
                    'to' => (int)$array[3],
                    'type' => (int)$array[4],
                    'weight' => (int)$array[5],
                ]);
            }
            $line = strtok($separator);
        }
        strtok('', '');

        foreach ($node->getNodes() as $id => $entity) {
            $node->setNode($entity);
            break;
        }

        $wrapper->setNode($node);

        if ($error !== null) {
            $wrapper->setCode(413)
                ->setReason($error);
        } else {
            $wrapper->setCode($response->getStatusCode())
                ->setReason($response->getReasonPhrase());
        }

        return $wrapper;
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