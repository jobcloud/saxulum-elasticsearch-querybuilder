<?php

declare(strict_types=1);

namespace Saxulum\ElasticSearchQueryBuilder\Node;

abstract class AbstractParentNode extends AbstractNode
{
    /** @var AbstractNode[] */
    protected array $children = [];
}
