<?php

namespace Fsv\SortableCollectionTypeBundle\Tests\Comparator;

use Fsv\SortableCollectionTypeBundle\Comparator\CallbackComparator;
use Symfony\Component\Form\FormView;

class CallbackComparatorTest extends \PHPUnit_Framework_TestCase
{
    public function testCompare()
    {
        $viewA = new FormView();
        $viewA->vars['data'] = ['property' => 0];
        $viewB = new FormView();
        $viewB->vars['data'] = ['property' => 1];

        $comparator = new CallbackComparator(function (FormView $viewA, FormView $viewB) {
            $valueA = $viewA->vars['data']['property'];
            $valueB = $viewB->vars['data']['property'];

            return $valueA > $valueB ? 1 : ($valueA < $valueB ? -1 : 0);
        });

        $this->assertEquals(-1, $comparator->compare($viewA, $viewB));
        $this->assertEquals(1, $comparator->compare($viewB, $viewA));
        $this->assertEquals(0, $comparator->compare($viewA, $viewA));
        $this->assertEquals(-1, $comparator($viewA, $viewB));
        $this->assertEquals(1, $comparator($viewB, $viewA));
        $this->assertEquals(0, $comparator($viewA, $viewA));
    }
}
