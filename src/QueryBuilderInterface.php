<?php

declare(strict_types=1);

namespace Saxulum\ElasticSearchQueryBuilder;

use Saxulum\ElasticSearchQueryBuilder\Node\AbstractNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ArrayNode;
use Saxulum\ElasticSearchQueryBuilder\Node\BoolNode;
use Saxulum\ElasticSearchQueryBuilder\Node\FloatNode;
use Saxulum\ElasticSearchQueryBuilder\Node\IntNode;
use Saxulum\ElasticSearchQueryBuilder\Node\NullNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNodeSerializeInterface;
use Saxulum\ElasticSearchQueryBuilder\Node\StringNode;

/**
 * @deprecated use base interface, query builder will be dropped
 */
interface QueryBuilderInterface extends ObjectNodeSerializeInterface
{
    /**
     * @throws \Exception
     */
    public function add(...$arguments): QueryBuilderInterface;

    /**
     * @throws \Exception
     */
    public function addToArrayNode(AbstractNode $node): QueryBuilderInterface;

    /**
     * @throws \Exception
     */
    public function addToObjectNode(string $key, AbstractNode $node): QueryBuilderInterface;

    /**
     * @throws \Exception
     */
    public function end(): QueryBuilderInterface;

    public function arrayNode(bool $allowSerializeEmpty = false): ArrayNode;

    public function boolNode(?bool $value = null, bool $allowSerializeEmpty = false): BoolNode;

    public function floatNode(?float $value = null, bool $allowSerializeEmpty = false): FloatNode;

    public function intNode(?int $value = null, bool $allowSerializeEmpty = false): IntNode;

    public function nullNode(): NullNode;

    public function objectNode(bool $allowSerializeEmpty = false): ObjectNode;

    public function stringNode(?string $value = null, bool $allowSerializeEmpty = false): StringNode;
}
