<?php

namespace Fsv\SortableCollectionTypeBundle\Tests\Sorter;

use Fsv\SortableCollectionTypeBundle\Comparator\CallbackComparator;
use Fsv\SortableCollectionTypeBundle\Comparator\ComparatorChain;
use Fsv\SortableCollectionTypeBundle\Comparator\PropertyComparator;
use Fsv\SortableCollectionTypeBundle\ComparatorInterface;
use Fsv\SortableCollectionTypeBundle\Normalizer\Normalizer;
use Fsv\SortableCollectionTypeBundle\NormalizerInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class NormalizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var NormalizerInterface
     */
    private $normalizer;

    protected function setUp()
    {
        $this->normalizer = new Normalizer($this->getMock(PropertyAccessorInterface::class));
    }

    public function testNormalizePropertyPath()
    {
        $comparator = $this->normalizer->normalize('property');

        $this->assertInstanceOf(PropertyComparator::class, $comparator);
        $this->assertSame('property', $comparator->getPropertyPath());
        $this->assertTrue($comparator->isAscending());
    }

    public function testNormalizePropertyPathList()
    {
        $comparator = $this->normalizer->normalize(['property1' => 'asc', 'property2' => 'desc']);

        $this->assertInstanceOf(ComparatorChain::class, $comparator);

        $chain = $comparator->all();

        $this->assertEquals(2, $comparator->count());
        $this->assertInstanceOf(PropertyComparator::class, $chain[0]);
        $this->assertInstanceOf(PropertyComparator::class, $chain[1]);
        $this->assertSame('property1', $chain[0]->getPropertyPath());
        $this->assertSame('property2', $chain[1]->getPropertyPath());
        $this->assertTrue($chain[0]->isAscending());
        $this->assertFalse($chain[1]->isAscending());
    }

    public function testNormalizePropertyPathListWithDefaultDirection()
    {
        $comparator = $this->normalizer->normalize(['property1', 'property2']);

        $this->assertInstanceOf(ComparatorChain::class, $comparator);

        $chain = $comparator->all();

        $this->assertEquals(2, $comparator->count());
        $this->assertInstanceOf(PropertyComparator::class, $chain[0]);
        $this->assertInstanceOf(PropertyComparator::class, $chain[1]);
        $this->assertSame('property1', $chain[0]->getPropertyPath());
        $this->assertSame('property2', $chain[1]->getPropertyPath());
        $this->assertTrue($chain[0]->isAscending());
        $this->assertTrue($chain[1]->isAscending());
    }

    public function testNormalizeCallback()
    {
        $callback = function (FormView $viewA, FormView $viewB) {};
        $comparator = $this->normalizer->normalize($callback);

        $this->assertInstanceOf(CallbackComparator::class, $comparator);
        $this->assertSame($callback, $comparator->getCallback());
    }

    public function testNormalizeComparator()
    {
        $comparator = $this->getMock(ComparatorInterface::class);

        $this->assertSame($comparator, $this->normalizer->normalize($comparator));
    }
}
