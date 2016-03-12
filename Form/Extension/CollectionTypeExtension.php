<?php

namespace Fsv\SortableCollectionTypeBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class CollectionTypeExtension extends AbstractTypeExtension
{
    /**
     * @var PropertyAccessorInterface
     */
    private $propertyAccessor;

    /**
     * @param PropertyAccessorInterface $propertyAccessor
     */
    public function __construct(PropertyAccessorInterface $propertyAccessor)
    {
        $this->propertyAccessor = $propertyAccessor;
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return method_exists(FormTypeInterface::class, 'getBlockPrefix')
            ? CollectionType::class
            : 'collection'
            ;
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        if (!$options['sort_by']) {
            return;
        }

        if (is_callable($options['sort_by'])) {
            usort($view->children, $options['sort_by']);
        } else {
            $sortBy = $this->normalizeSortOrderings($options['sort_by']);
            usort($view->children, function (FormView $viewA, FormView $viewB) use ($sortBy) {
                return $this->compareView($viewA, $viewB, $sortBy);
            });
        }
    }

    private function normalizeSortOrderings($sortBy)
    {
        $normalizedSortBy = [];

        foreach ($sortBy as $key => $value) {
            if (is_int($key)) {
                $normalizedSortBy[$value] = 'asc';
            } else {
                $normalizedSortBy[$key] = $value;
            }
        }

        return $normalizedSortBy;
    }

    private function compareView(FormView $viewA, FormView $viewB, array $sortBy)
    {
        foreach ($sortBy as $propertyPath => $direction) {
            $valueA = $this->propertyAccessor->getValue($viewA->vars['data'], $propertyPath);
            $valueB = $this->propertyAccessor->getValue($viewB->vars['data'], $propertyPath);

            if ($valueA > $valueB) {
                return $direction === 'asc' ? 1 : -1;
            } elseif ($valueA < $valueB) {
                return $direction === 'asc' ? -1 : 1;
            }
        }

        return 0;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('sort_by', [])
            ->setAllowedTypes('sort_by', ['array', 'callable'])
        ;
    }
}
