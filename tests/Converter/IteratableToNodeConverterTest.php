<?php

declare(strict_types=1);

namespace Saxulum\Tests\ElasticSearchQueryBuilder\Converter;

use PHPUnit\Framework\TestCase;
use Saxulum\ElasticSearchQueryBuilder\Converter\IteratableToNodeConverter;
use Saxulum\ElasticSearchQueryBuilder\Converter\ScalarToNodeConverterInterface;
use Saxulum\ElasticSearchQueryBuilder\Node\BoolNode;
use Saxulum\ElasticSearchQueryBuilder\Node\FloatNode;
use Saxulum\ElasticSearchQueryBuilder\Node\IntNode;
use Saxulum\ElasticSearchQueryBuilder\Node\NullNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;
use Saxulum\ElasticSearchQueryBuilder\Node\StringNode;

/**
 * @covers \Saxulum\ElasticSearchQueryBuilder\Converter\IteratableToNodeConverter
 */
class IteratableToNodeConverterTest extends TestCase
{
    public function testConvert()
    {
        $iteratableConverter = new IteratableToNodeConverter($this->getScalarToNodeConverter());

        $node = $iteratableConverter->convert([
            'arrayWithIndicies' => [
                0 => true,
                1 => 1.1234,
                2 => 1,
                3 => null,
                4 => 'string',
            ],
            'arrayWithout' => [
                true,
                1.1234,
                1,
                null,
                'string',
            ],
            'invalidArray' => [
                0 => true,
                1 => 1.1234,
                3 => 1,
                4 => null,
                5 => 'string',
            ],
            0 => 'value',
            'emptyArray' => []
        ]);

        $expected = <<<EOD
{
    "arrayWithIndicies": [
        true,
        1.1234,
        1,
        null,
        "string"
    ],
    "arrayWithout": [
        true,
        1.1234,
        1,
        null,
        "string"
    ],
    "invalidArray": {
        "0": true,
        "1": 1.1234,
        "3": 1,
        "4": null,
        "5": "string"
    },
    "0": "value"
}
EOD;

        self::assertInstanceOf(ObjectNode::class, $node);
        self::assertSame($expected, $node->json(true));
    }

    public function testConvertWithAllowEmptySerialze()
    {
        $iteratableConverter = new IteratableToNodeConverter($this->getScalarToNodeConverter());

        $node = $iteratableConverter->convert(['emptyArray' => []], '', true);

        $expected = <<<EOD
{
    "emptyArray": []
}
EOD;

        self::assertEquals($expected, $node->json(true));
    }

    public function testWithoutArrayOrTraversableExpectException()
    {
        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage('Params need to be array or Traversable');

        $iteratableConverter = new IteratableToNodeConverter($this->getScalarToNodeConverter());
        $iteratableConverter->convert(new \DateTime());
    }

    public function testWithUnsupportedValueExpectException()
    {
        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage('Type DateTime is not supported, at path dates[0]');

        $iteratableConverter = new IteratableToNodeConverter($this->getScalarToNodeConverter());
        $iteratableConverter->convert(['dates' => [new \DateTime()]]);
    }

    /**
     * @return ScalarToNodeConverterInterface
     */
    private function getScalarToNodeConverter(): ScalarToNodeConverterInterface
    {
        /** @var ScalarToNodeConverterInterface|\PHPUnit_Framework_MockObject_MockObject $scalarToNodeConverter */
        $scalarToNodeConverter = $this
            ->getMockBuilder(ScalarToNodeConverterInterface::class)
            ->setMethods(['convert'])
            ->getMockForAbstractClass();

        $scalarToNodeConverter->expects(self::any())->method('convert')->willReturnCallback(
            function ($value, string $path = '') {
                $type = gettype($value);

                if ('boolean' === $type) {
                    return BoolNode::create($value);
                }

                if ('double' === $type) {
                    return FloatNode::create($value);
                }

                if ('integer' === $type) {
                    return IntNode::create($value);
                }

                if ('NULL' === $type) {
                    return NullNode::create($value);
                }

                if ('string' === $type) {
                    return StringNode::create($value);
                }

                throw new \InvalidArgumentException(
                    sprintf(
                        'Type %s is not supported, at path %s',
                        is_object($value) ? get_class($value) : $type, $path
                    )
                );
            }
        );

        return $scalarToNodeConverter;
    }
}
