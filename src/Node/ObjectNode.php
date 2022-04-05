<?php

declare(strict_types=1);

namespace Saxulum\ElasticSearchQueryBuilder\Node;

final class ObjectNode extends AbstractParentNode implements ObjectNodeSerializeInterface
{
    public static function create(bool $allowSerializeEmpty = false): ObjectNode
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

        foreach ($children as $key => $node) {
            $this->add($key, clone $node);
        }
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function add(string $key, AbstractNode $node): self
    {
        if (isset($this->children[$key])) {
            throw new \InvalidArgumentException(sprintf('There is already a node with key %s!', $key));
        }

        $node->setParent($this);

        $this->children[$key] = $node;

        return $this;
    }

    public function serializeEmpty(): \stdClass
    {
        return new \stdClass();
    }

    public function serialize(): ?\stdClass
    {
        $serialized = new \stdClass();
        foreach ($this->children as $key => $child) {
            $this->serializeChild($serialized, (string) $key, $child);
        }

        if ([] === (array) $serialized) {
            return null;
        }

        return $serialized;
    }

    private function serializeChild(\stdClass $serialized, string $key, AbstractNode $child): void
    {
        if (null !== $serializedChild = $child->serialize()) {
            $serialized->$key = $serializedChild;
        } elseif ($child->isAllowSerializeEmpty()) {
            $serialized->$key = $child->serializeEmpty();
        }
    }

    public function json(bool $beautify = false): string
    {
        if (null === $serialized = $this->serialize()) {
            return '';
        }

        if ($beautify) {
            return json_encode($serialized, JSON_PRETTY_PRINT);
        }

        return json_encode($serialized);
    }
}
