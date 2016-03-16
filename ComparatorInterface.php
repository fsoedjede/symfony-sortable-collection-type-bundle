<?php

namespace Fsv\SortableCollectionTypeBundle;

use Symfony\Component\Form\FormView;

interface ComparatorInterface
{
    /**
     * @param FormView $viewA
     * @param FormView $viewB
     * @return int
     */
    public function compare(FormView $viewA, FormView $viewB);

    /**
     * @param FormView $viewA
     * @param FormView $viewB
     * @return int
     */
    public function __invoke(FormView $viewA, FormView $viewB);
}
