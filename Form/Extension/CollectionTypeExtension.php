<?php

namespace Fsv\SortableCollectionTypeBundle\Form\Extension;

use Fsv\SortableCollectionTypeBundle\NormalizerInterface;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CollectionTypeExtension extends AbstractTypeExtension
{
    /**
     * @var NormalizerInterface
     */
    private $normalizer;

    /**
     * @param NormalizerInterface $normalizer
     */
    public function __construct(NormalizerInterface $normalizer)
    {
        $this->normalizer = $normalizer;
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
        if ($options['sort_by']) {
            // We can't use invokable object because of bug https://bugs.php.net/bug.php?id=50688 ?
            usort($view->children, [$options['sort_by'], 'compare']);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('sort_by', null)
            ->setNormalizer('sort_by', function (Options $options, $value) {
                if (null !== $value) {
                    return $this->normalizer->normalize($value);
                }

                return null;
            })
        ;
    }
}
