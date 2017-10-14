<?php
/**
 * Created by PhpStorm.
 * User: stardisblue
 * Date: 11/10/2017
 * Time: 22:13
 */

namespace Meliblue;


class WordWrapper
{
    private $node;
    private $code;
    private $reason;

    /**
     * @return RawNode
     */
    public function getNode(): ?RawNode
    {
        return $this->node;
    }

    /**
     * @param RawNode $node
     */
    public function setNode(RawNode $node)
    {
        $this->node = $node;
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @param int $code
     */
    public function setCode(int $code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getReason(): string
    {
        return $this->reason;
    }

    /**
     * @param string $reason
     */
    public function setReason(string $reason)
    {
        $this->reason = $reason;
    }


}