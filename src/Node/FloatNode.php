<?php

declare(strict_types=1);

namespace Saxulum\ElasticSearchQueryBuilder\Node;

final class FloatNode extends AbstractNode
{
    private ?float $value;

    public static function create(?float $value = null, bool $allowSerializeEmpty = false): FloatNode
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

    public function serialize(): ?float
    {
        return $this->value;
    }
}
