<?php

declare(strict_types=1);

namespace Saxulum\ElasticSearchQueryBuilder\Converter;

use Saxulum\ElasticSearchQueryBuilder\Node\AbstractNode;

interface ScalarToNodeConverterInterface
{
    /**
     * @param bool|float|int|string|null $value
     * @throws \InvalidArgumentException
     *
     * @todo add $allowSerializeEmpty to next major version
     */
    public function convert(mixed $value, string $path = ''): AbstractNode;
}
