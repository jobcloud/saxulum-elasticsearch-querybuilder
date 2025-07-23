<?php

declare(strict_types=1);

namespace Saxulum\ElasticSearchQueryBuilder\Node;

final class IntNode extends AbstractNode
{
    private ?int $value;

    public static function create(?int $value = null, bool $allowSerializeEmpty = false): IntNode
    {
        $node = new self();
        $node->value = $value;
        $node->allowSerializeEmpty = $allowSerializeEmpty;

        return $node;
    }

    public function serializeEmpty()
    {
        return null;
    }

    public function serialize(): ?int
    {
        return $this->value;
    }
}
