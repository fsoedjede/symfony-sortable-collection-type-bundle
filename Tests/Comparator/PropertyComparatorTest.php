<?php

namespace Fsv\SortableCollectionTypeBundle\Tests\Comparator;

use Fsv\SortableCollectionTypeBundle\Comparator\PropertyComparator;
use Symfony\Component\Form\FormView;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class PropertyComparatorTest extends \PHPUnit_Framework_TestCase
{
    public function testAscendingComparator()
    {
        $viewA = new FormView();
        $viewA->vars['data'] = ['property' => 0];
        $viewB = new FormView();
        $viewB->vars['data'] = ['property' => 1];

        $propertyAccessor = $this->getMock(PropertyAccessorInterface::class);
        $propertyAccessor
            ->expects($this->exactly(6))
            ->method('getValue')
            ->willReturnCallback(function ($data, $propertyPath) {
                return $data['property'];
            })
        ;
        $comparator = new PropertyComparator('[property]', true, $propertyAccessor);

        $this->assertEquals(-1, $comparator->compare($viewA, $viewB));
        $this->assertEquals(1, $comparator->compare($viewB, $viewA));
        $this->assertEquals(0, $comparator->compare($viewA, $viewA));
    }

    public function testDescendingComparator()
    {
        $viewA = new FormView();
        $viewA->vars['data'] = ['property' => 0];
        $viewB = new FormView();
        $viewB->vars['data'] = ['property' => 1];

        $propertyAccessor = $this->getMock(PropertyAccessorInterface::class);
        $propertyAccessor
            ->expects($this->exactly(6))
            ->method('getValue')
            ->willReturnCallback(function ($data, $propertyPath) {
                return $data['property'];
            })
        ;
        $comparator = new PropertyComparator('[property]', false, $propertyAccessor);

        $this->assertEquals(1, $comparator->compare($viewA, $viewB));
        $this->assertEquals(-1, $comparator->compare($viewB, $viewA));
        $this->assertEquals(0, $comparator->compare($viewA, $viewA));
    }
}
