<?php

declare(strict_types=1);

namespace Saxulum\ElasticSearchQueryBuilder\Node;

final class BoolNode extends AbstractNode
{
    private ?bool $value;

    public static function create(?bool $value = null, bool $allowSerializeEmpty = false): BoolNode
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

    public function serialize(): ?bool
    {
        return $this->value;
    }
}
