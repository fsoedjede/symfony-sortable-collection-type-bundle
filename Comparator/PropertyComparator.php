<?php

namespace Fsv\SortableCollectionTypeBundle\Comparator;

use Symfony\Component\Form\FormView;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class PropertyComparator extends AbstractComparator
{
    private $propertyPath;
    private $ascending;
    private $propertyAccessor;

    public function __construct($propertyPath, $ascending, PropertyAccessorInterface $propertyAccessor)
    {
        $this->propertyPath = $propertyPath;
        $this->ascending = $ascending;
        $this->propertyAccessor = $propertyAccessor;
    }

    public function compare(FormView $viewA, FormView $viewB)
    {
        $valueA = $this->propertyAccessor->getValue($viewA->vars['data'], $this->propertyPath);
        $valueB = $this->propertyAccessor->getValue($viewB->vars['data'], $this->propertyPath);

        return $valueA > $valueB ? 1 : ($valueA < $valueB ? -1 : 0);
    }

    public function getPropertyPath()
    {
        return $this->propertyPath;
    }

    public function isAscending()
    {
        return $this->ascending;
    }
}
