<?php

declare(strict_types=1);

namespace Saxulum\ElasticSearchQueryBuilder\Node;

final class ArrayNode extends AbstractParentNode
{
    public static function create(bool $allowSerializeEmpty = false): ArrayNode
    {
        $node = new self();
        $node->allowSerializeEmpty = $allowSerializeEmpty;

        return $node;
    }

    public function __clone()
    {
        parent::__clone();

        $children = $this->children;

        $this->children = [];

        foreach ($children as $node) {
            $this->add(clone $node);
        }
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function add(AbstractNode $node): self
    {
        $node->setParent($this);

        $this->children[] = $node;

        return $this;
    }

    public function serializeEmpty(): array
    {
        return [];
    }

    public function serialize(): ?array
    {
        $serialized = [];
        foreach ($this->children as $child) {
            $this->serializeChild($serialized, $child);
        }

        if ([] === $serialized) {
            return null;
        }

        return $serialized;
    }

    private function serializeChild(array &$serialized, AbstractNode $child): void
    {
        if (null !== $serializedChild = $child->serialize()) {
            $serialized[] = $serializedChild;
        } elseif ($child->isAllowSerializeEmpty()) {
            $serialized[] = $child->serializeEmpty();
        }
    }
}
