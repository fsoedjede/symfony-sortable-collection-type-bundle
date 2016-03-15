<?php

namespace Fsv\SortableCollectionTypeBundle\Tests\Form\Extension;

use Fsv\SortableCollectionTypeBundle\ComparatorInterface;
use Fsv\SortableCollectionTypeBundle\Form\Extension\CollectionTypeExtension;
use Fsv\SortableCollectionTypeBundle\NormalizerInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

class CollectionTypeExtensionTest extends TypeTestCase
{
    private static $collectionType;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::$collectionType = method_exists(FormTypeInterface::class, 'getBlockPrefix')
            ? CollectionType::class
            : 'collection'
        ;
    }

    public function testCreateViewWithoutSorting()
    {
        $form = $this->factory->create(
            self::$collectionType,
            [
                ['property' => 2],
                ['property' => 1],
                ['property' => 0],
                ['property' => 3]
            ]
        );

        $this->assertEquals([2, 1, 0, 3], $this->extractPropertyValuesFromView('property', $form->createView()));
    }

    public function testCreateViewWithSorting()
    {
        $form = $this->factory->create(
            self::$collectionType,
            [
                ['property' => 2],
                ['property' => 1],
                ['property' => 0],
                ['property' => 3]
            ],
            [
                'sort_by' => '[property]'
            ]
        );

        $this->assertEquals([0, 1, 2, 3], $this->extractPropertyValuesFromView('property', $form->createView()));
    }

    private function extractPropertyValuesFromView($propertyName, $view)
    {
        return array_map(function ($view) use ($propertyName) {
            return $view->vars['data'][$propertyName];
        }, $view->children);
    }

    protected function getExtensions()
    {
        $comparator = $this->getMock(ComparatorInterface::class);
        $comparator
            ->expects($this->any())
            ->method('compare')
            ->will($this->returnCallback(function (FormView $viewA, FormView $viewB) {
                $valueA = $viewA->vars['data']['property'];
                $valueB = $viewB->vars['data']['property'];

                return $valueA > $valueB ? 1 : ($valueA < $valueB ? -1 : 0);
            }))
        ;

        $normalizer = $this->getMock(NormalizerInterface::class);
        $normalizer
            ->expects($this->any())
            ->method('normalize')
            ->with('[property]')
            ->will($this->returnCallback(function () use ($comparator) {
                return $comparator;
            }));
        ;

        return [
            new PreloadedExtension(
                [],
                [
                    self::$collectionType => [
                        new CollectionTypeExtension($normalizer)
                    ]
                ]
            )
        ];
    }
}
