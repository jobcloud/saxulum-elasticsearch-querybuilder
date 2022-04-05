<?php

declare(strict_types=1);

namespace Saxulum\ElasticSearchQueryBuilder\Converter;

use Saxulum\ElasticSearchQueryBuilder\Node\AbstractNode;
use Saxulum\ElasticSearchQueryBuilder\Node\AbstractParentNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ArrayNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;

final class IteratableToNodeConverter implements IteratableToNodeConverterInterface
{
    private ScalarToNodeConverterInterface $scalarToNodeConverter;

    public function __construct(ScalarToNodeConverterInterface $scalarToNodeConverter)
    {
        $this->scalarToNodeConverter = $scalarToNodeConverter;
    }

    /**
     * @param mixed  $data
     * @param string $path
     * @param bool   $allowSerializeEmpty
     * @return ArrayNode|ObjectNode
     * @throws \InvalidArgumentException
     */
    public function convert($data, string $path = '', bool $allowSerializeEmpty = false): AbstractParentNode
    {
        if (!is_iterable($data)) {
            throw new \InvalidArgumentException('Parameters need to be iterable.');
        }

        if ($this->isArray($data)) {
            return $this->addChildNodeForArray($data, $path, $allowSerializeEmpty);
        }

        return $this->addChildNodeForObject($data, $path, $allowSerializeEmpty);
    }

    private function isArray(iterable $data): bool
    {
        $counter = 0;
        foreach ($data as $key => $value) {
            if ($key !== $counter) {
                return false;
            }

            ++$counter;
        }

        return true;
    }

    private function addChildNodeForArray(
        iterable $data,
        string $path,
        bool $allowSerializeEmpty
    ): ArrayNode {
        $parentNode = ArrayNode::create($allowSerializeEmpty);

        foreach ($data as $key => $value) {
            $subPath = $path . '[' . $key . ']';
            $node = $this->getNode($value, $subPath, $allowSerializeEmpty);

            $parentNode->add($node);
        }

        return $parentNode;
    }

    private function addChildNodeForObject(
        iterable $data,
        string $path,
        bool $allowSerializeEmpty
    ): ObjectNode {
        $parentNode = ObjectNode::create($allowSerializeEmpty);

        foreach ($data as $key => $value) {
            $key = (string) $key;
            $subPath = '' !== $path ? $path . '.' . $key : $key;
            $node = $this->getNode($value, $subPath, $allowSerializeEmpty);

            $parentNode->add($key, $node);
        }

        return $parentNode;
    }

    /**
     * @param mixed  $value
     * @param string $path
     * @param bool   $allowSerializeEmpty
     *
     * @return AbstractNode
     */
    private function getNode($value, string $path, bool $allowSerializeEmpty): AbstractNode
    {
        if (is_iterable($value)) {
            return $this->convert($value, $path, $allowSerializeEmpty);
        }

        return $this->scalarToNodeConverter->convert($value, $path);
    }
}
