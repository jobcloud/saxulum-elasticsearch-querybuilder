<?php

declare(strict_types=1);

namespace Saxulum\ElasticSearchQueryBuilder\Node;

final class NullNode extends AbstractNode
{
    public static function create(): NullNode
    {
        $node = new self();
        $node->allowSerializeEmpty = true;

        return $node;
    }

    public function serializeEmpty()
    {
        return null;
    }

    public function serialize()
    {
        return null;
    }
}
