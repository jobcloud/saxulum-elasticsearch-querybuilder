<?php

declare(strict_types=1);

namespace Saxulum\ElasticSearchQueryBuilder\Node;

abstract class AbstractNode
{
    protected ?AbstractParentNode $parent = null;

    protected bool $allowSerializeEmpty;

    protected function __construct()
    {
    }

    public function __clone()
    {
        $this->parent = null;
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function setParent(AbstractParentNode $parent)
    {
        if (null !== $this->parent) {
            throw new \InvalidArgumentException('Node already got a parent!');
        }

        $this->parent = $parent;
    }

    public function getParent(): ?AbstractParentNode
    {
        return $this->parent;
    }

    public function isAllowSerializeEmpty(): bool
    {
        return $this->allowSerializeEmpty;
    }

    /**
     * @return \stdClass|array|null
     */
    abstract public function serializeEmpty();

    /**
     * @return \stdClass|array|string|float|int|bool|null
     */
    abstract public function serialize();
}
