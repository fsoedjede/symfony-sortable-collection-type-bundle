<?php

namespace Fsv\SortableCollectionTypeBundle\Tests\Comparator;

use Fsv\SortableCollectionTypeBundle\Comparator\ComparatorChain;
use Fsv\SortableCollectionTypeBundle\ComparatorInterface;
use Symfony\Component\Form\FormView;

class ComparatorChainTest extends \PHPUnit_Framework_TestCase
{
    public function testCompare()
    {
        $viewA = new FormView();
        $viewB = new FormView();

        $firstComparator = $this->getMock(ComparatorInterface::class);
        $firstComparator
            ->expects($this->exactly(2))
            ->method('compare')
            ->with($viewA, $viewB)
            ->will($this->returnValue(0))
        ;
        $secondComparator = $this->getMock(ComparatorInterface::class);
        $secondComparator
            ->expects($this->exactly(2))
            ->method('compare')
            ->with($viewA, $viewB)
            ->will($this->returnValue(1))
        ;

        $comparator = new ComparatorChain();
        $comparator->add($firstComparator);
        $comparator->add($secondComparator);

        $this->assertSame([$firstComparator, $secondComparator], $comparator->all());
        $this->assertEquals(1, $comparator->compare($viewA, $viewB));
        $this->assertEquals(1, $comparator($viewA, $viewB));
    }
}
