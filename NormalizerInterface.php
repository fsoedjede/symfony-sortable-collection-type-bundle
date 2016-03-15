<?php

namespace Fsv\SortableCollectionTypeBundle;

interface NormalizerInterface
{
    /**
     * @param mixed $sortBy
     * @return ComparatorInterface
     */
    public function normalize($sortBy);
}
