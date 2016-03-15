<?php

namespace Fsv\SortableCollectionTypeBundle\Comparator;

use Fsv\SortableCollectionTypeBundle\ComparatorInterface;
use Symfony\Component\Form\FormView;

class CallbackComparator implements ComparatorInterface
{
    /**
     * @var callable
     */
    private $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function compare(FormView $viewA, FormView $viewB)
    {
        return call_user_func($this->callback, $viewA, $viewB);
    }

    public function getCallback()
    {
        return $this->callback;
    }
}
