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

        usort($view->children, function ($objectA, $objectB) use ($options) {
            foreach ($options['sort_by'] as $key => $value) {
                if (is_int($key)) {
                    $propertyPath = $value;
                    $direction = 'asc';
                } else {
                    $propertyPath = $key;
                    $direction = $value;
                }

                $valueA = $this->propertyAccessor->getValue($objectA->vars['data'], $propertyPath);
                $valueB = $this->propertyAccessor->getValue($objectB->vars['data'], $propertyPath);

                if ($valueA > $valueB) {
                    return $direction === 'asc' ? 1 : -1;
                } elseif ($valueA < $valueB) {
                    return $direction === 'asc' ? -1 : 1;
                }
            }

            return 0;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('sort_by', [])
            ->setAllowedTypes('sort_by', 'array')
        ;
    }
}
