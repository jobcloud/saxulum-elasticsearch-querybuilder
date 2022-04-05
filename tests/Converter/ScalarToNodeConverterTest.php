<?php

declare(strict_types=1);

namespace Saxulum\Tests\ElasticSearchQueryBuilder\Converter;

use PHPUnit\Framework\TestCase;
use Saxulum\ElasticSearchQueryBuilder\Converter\ScalarToNodeConverter;
use Saxulum\ElasticSearchQueryBuilder\Node\BoolNode;
use Saxulum\ElasticSearchQueryBuilder\Node\FloatNode;
use Saxulum\ElasticSearchQueryBuilder\Node\IntNode;
use Saxulum\ElasticSearchQueryBuilder\Node\NullNode;
use Saxulum\ElasticSearchQueryBuilder\Node\StringNode;

/**
 * @covers \Saxulum\ElasticSearchQueryBuilder\Converter\ScalarToNodeConverter
 */
class ScalarToNodeConverterTest extends TestCase
{
    public function testConvertBool(): void
    {
        $valueConverter = new ScalarToNodeConverter();

        self::assertInstanceOf(BoolNode::class, $valueConverter->convert(true));
    }

    public function testConvertFloat(): void
    {
        $valueConverter = new ScalarToNodeConverter();

        self::assertInstanceOf(FloatNode::class, $valueConverter->convert(1.234));
    }

    public function testConvertInt(): void
    {
        $valueConverter = new ScalarToNodeConverter();

        self::assertInstanceOf(IntNode::class, $valueConverter->convert(1));
    }

    public function testConvertNull(): void
    {
        $valueConverter = new ScalarToNodeConverter();

        self::assertInstanceOf(NullNode::class, $valueConverter->convert(null));
    }

    public function testConvertString(): void
    {
        $valueConverter = new ScalarToNodeConverter();

        self::assertInstanceOf(StringNode::class, $valueConverter->convert('string'));
    }

    public function testConvertSetsDefaultIsAllowSerializeEmpty(): void
    {
        $valueConverter = new ScalarToNodeConverter();

        self::assertFalse($valueConverter->convert('string')->isAllowSerializeEmpty());
    }

    public function testConvertSetsIsAllowSerializeEmpty(): void
    {
        $valueConverter = new ScalarToNodeConverter();

        self::assertTrue($valueConverter->convert('string', '', true)->isAllowSerializeEmpty());
    }

    public function testConvertWithUnsupportedValueExpectException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Type DateTime is not supported, at path dates[0]');

        $valueConverter = new ScalarToNodeConverter();
        $valueConverter->convert(new \DateTime(), 'dates[0]');
    }
}
