<?php

declare(strict_types=1);

namespace Saxulum\ElasticSearchQueryBuilder\Converter;

use Saxulum\ElasticSearchQueryBuilder\Node\AbstractParentNode;

interface IteratableToNodeConverterInterface
{
    /**
     * @throws \InvalidArgumentException
     *
     * @todo add $path to next major version
     * @todo add $allowSerializeEmpty to next major version
     */
    public function convert(mixed $data): AbstractParentNode;
}
