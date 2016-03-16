<?php

namespace Fsv\SortableCollectionTypeBundle\Comparator;

use Fsv\SortableCollectionTypeBundle\ComparatorInterface;
use Symfony\Component\Form\FormView;

class ComparatorChain extends AbstractComparator implements \Countable
{
    /**
     * @var ComparatorInterface[]
     */
    private $comparators = [];

    /**
     * @param ComparatorInterface $comparator
     */
    public function add(ComparatorInterface $comparator)
    {
        $this->comparators[] = $comparator;
    }

    /**
     * @return ComparatorInterface[]
     */
    public function all()
    {
        return $this->comparators;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->comparators);
    }

    /**
     * {@inheritdoc}
     */
    public function compare(FormView $viewA, FormView $viewB)
    {
        foreach ($this->comparators as $comparator) {
            if (0 !== ($result = $comparator->compare($viewA, $viewB))) {
                return $result;
            }
        }

        return 0;
    }
}
