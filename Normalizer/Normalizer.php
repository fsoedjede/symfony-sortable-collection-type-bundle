<?php

namespace Fsv\SortableCollectionTypeBundle\Normalizer;

use Fsv\SortableCollectionTypeBundle\ComparatorInterface;
use Fsv\SortableCollectionTypeBundle\NormalizerInterface;
use Fsv\SortableCollectionTypeBundle\Comparator\CallbackComparator;
use Fsv\SortableCollectionTypeBundle\Comparator\ComparatorChain;
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
        } elseif ($sortBy instanceof \Closure) {
            return new CallbackComparator($sortBy);
        } elseif ($sortBy instanceof ComparatorInterface) {
            return $sortBy;
        } elseif (is_array($sortBy)) {
            $comparator = new ComparatorChain();

            foreach ($sortBy as $key => $value) {
                if (is_int($key)) {
                    $comparator->add($this->createPropertyComparator($value));
                } else {
                    $comparator->add($this->createPropertyComparator($key, $value !== 'desc'));
                }
            }

            return $comparator;
        }

        throw new \InvalidArgumentException("Parameter must be a string, array, closure or object");
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
