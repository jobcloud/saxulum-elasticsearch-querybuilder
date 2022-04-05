<?php

declare(strict_types=1);

namespace Saxulum\ElasticSearchQueryBuilder\Node;

interface ObjectNodeSerializeInterface
{
    public function serialize(): ?\stdClass;

    public function json(bool $beautify = false): string;
}
