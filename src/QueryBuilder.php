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
use Saxulum\ElasticSearchQueryBuilder\Node\StringNode;

/**
 * @deprecated use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode
 */
final class QueryBuilder implements QueryBuilderInterface
{
    private ObjectNode $rootNode;

    private ?AbstractNode $node;

    public function __construct()
    {
        @trigger_error(sprintf('Use "%s" instead of the "%s"', ObjectNode::class, self::class), E_USER_DEPRECATED);

        $this->rootNode = ObjectNode::create();
        $this->node = $this->rootNode;
    }

    /**
     * @throws \Exception
     */
    public function add(...$arguments): QueryBuilderInterface
    {
        if ($this->node instanceof ObjectNode) {
            return $this->addToObjectNode(...$arguments);
        }

        return $this->addToArrayNode(...$arguments);
    }

    /**
     * @throws \Exception
     */
    public function addToArrayNode(AbstractNode $node): QueryBuilderInterface
    {
        if (!$this->node instanceof ArrayNode) {
            throw new \Exception(sprintf('You cannot call %s on node type: %s', __FUNCTION__, get_class($this->node)));
        }

        $this->node->add($node);
        $this->reassignParent($node);

        return $this;
    }

    /**
     * @throws \Exception
     */
    public function addToObjectNode(string $key, AbstractNode $node): QueryBuilderInterface
    {
        if (!$this->node instanceof ObjectNode) {
            throw new \Exception(sprintf('You cannot call %s on node type: %s', __FUNCTION__, get_class($this->node)));
        }

        $this->node->add($key, $node);
        $this->reassignParent($node);

        return $this;
    }

    private function reassignParent(AbstractNode $node): void
    {
        if ($node instanceof ArrayNode || $node instanceof ObjectNode) {
            $this->node = $node;
        }
    }

    /**
     * @throws \Exception
     */
    public function end(): QueryBuilderInterface
    {
        if (null === $this->node = $this->node->getParent()) {
            throw new \Exception(sprintf('You cannot call %s on main node', __FUNCTION__));
        }

        return $this;
    }

    public function arrayNode(bool $allowSerializeEmpty = false): ArrayNode
    {
        return ArrayNode::create($allowSerializeEmpty);
    }

    public function boolNode($value = null, bool $allowSerializeEmpty = false): BoolNode
    {
        return BoolNode::create($value, $allowSerializeEmpty);
    }

    public function floatNode($value = null, bool $allowSerializeEmpty = false): FloatNode
    {
        return FloatNode::create($value, $allowSerializeEmpty);
    }

    public function intNode($value = null, bool $allowSerializeEmpty = false): IntNode
    {
        return IntNode::create($value, $allowSerializeEmpty);
    }

    public function nullNode(): NullNode
    {
        return NullNode::create();
    }

    public function objectNode(bool $allowSerializeEmpty = false): ObjectNode
    {
        return ObjectNode::create($allowSerializeEmpty);
    }

    public function stringNode(?string $value = null, bool $allowSerializeEmpty = false): StringNode
    {
        return StringNode::create($value, $allowSerializeEmpty);
    }

    public function serialize(): ?\stdClass
    {
        return $this->rootNode->serialize();
    }

    public function json(bool $beautify = false): string
    {
        if (null === $serialized = $this->serialize()) {
            return '';
        }

        if ($beautify) {
            return json_encode($serialized, JSON_PRETTY_PRINT);
        }

        return json_encode($serialized);
    }
}
