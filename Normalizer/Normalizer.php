<?php

namespace Fsv\SortableCollectionTypeBundle\Normalizer;

use Fsv\SortableCollectionTypeBundle\ComparatorInterface;
use Fsv\SortableCollectionTypeBundle\NormalizerInterface;
use Fsv\SortableCollectionTypeBundle\Comparator\CallbackComparator;
use Fsv\SortableCollectionTypeBundle\Comparator\PropertyComparator;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class Normalizer implements NormalizerInterface
{
    private $propertyAccessor;

    public function __construct(PropertyAccessorInterface $propertyAccessor)
    {
        $this->propertyAccessor = $propertyAccessor;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($sortBy)
    {
        if (is_string($sortBy)) {
            return $this->createPropertyComparator($sortBy);
        } elseif (is_callable($sortBy)) {
            return new CallbackComparator($sortBy);
        } elseif (is_object($sortBy) && $sortBy instanceof ComparatorInterface) {
            return $sortBy;
        }

        throw new \InvalidArgumentException("Parameter must be a string, callable or object");
    }

    /**
     * @param string $propertyPath
     * @param bool $ascending
     * @return PropertyComparator
     */
    private function createPropertyComparator($propertyPath, $ascending = true)
    {
        return new PropertyComparator($propertyPath, $ascending, $this->propertyAccessor);
    }
}
