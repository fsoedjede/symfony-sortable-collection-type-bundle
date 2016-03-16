<?php

namespace Fsv\SortableCollectionTypeBundle\Comparator;

use Fsv\SortableCollectionTypeBundle\ComparatorInterface;
use Symfony\Component\Form\FormView;

abstract class AbstractComparator implements ComparatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(FormView $viewA, FormView $viewB)
    {
        return $this->compare($viewA, $viewB);
    }
}
