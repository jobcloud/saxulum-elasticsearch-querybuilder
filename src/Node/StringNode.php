<?php

declare(strict_types=1);

namespace Saxulum\ElasticSearchQueryBuilder\Node;

final class StringNode extends AbstractNode
{
    private ?string $value;

    public static function create(?string $value = null, bool $allowSerializeEmpty = false): StringNode
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

    public function serialize(): ?string
    {
        return $this->value;
    }
}
