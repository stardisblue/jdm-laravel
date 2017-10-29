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

        $contents = $response->getBody()->getContents();


        $domDoc = new \DOMDocument('1.0', 'ISO-8859-1');
        @$domDoc->loadHTML($contents);

        $content = $domDoc->getElementsByTagName('code')->item(0);

        if ($content === null) {
            $wrapper->setCode(404);
            $wrapper->setReason("word does not exist");
        } elseif ($domDoc->getElementsByTagName("warning")->length > 0) {
            $wrapper->setNode(self::extract($content));
            $wrapper->setCode(413);
            $wrapper->setReason("TOOBIG_USE_DUMP");
        } else {
            $wrapper->setNode(self::extract($content));
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
        $description = $DOMElement->getElementsByTagName("def")->item(0)->textContent;

        $node = new RawNode();
        $node->setDescription($description);
        $separator = "\n";

        $line = strtok($content, $separator);
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
                if (filter_var($array[3], FILTER_VALIDATE_INT) === false) {
                    $array[2] = $array[2].';'.array_splice($array, 3, 1)[0];
                }

                $entity = new SimpleNode();
                $entity->setId($array[1])
                    ->setName($array[2])
                    ->setNodeType($array[3])
                    ->setWeight($array[4])
                    ->setFormattedName(isset($array[5]) ? $array[5] : null);

                $node->addSimpleNode($entity);
            } elseif ($type === 'rt') {
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