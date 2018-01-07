<?php namespace Meliblue;


use Meliblue\Models\RawNode;

class WordWrapper
{
    private $node;
    private $code;
    private $reason;

    /**
     * @return RawNode
     */
    public function getNode(): RawNode
    {
        return $this->node;
    }

    /**
     * @param RawNode $node
     * @return $this
     */
    public function setNode(RawNode $node): self
    {
        $this->node = $node;

        return $this;
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
     *
     * @return $this
     */
    public function setCode(int $code): self
    {
        $this->code = $code;

        return $this;
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
     * @return $this
     */
    public function setReason(string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }


}