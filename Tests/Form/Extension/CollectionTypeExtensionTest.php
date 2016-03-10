<?php

namespace Fsv\SortableCollectionTypeBundle\Tests\Form\Extension;

use Fsv\SortableCollectionTypeBundle\Form\Extension\CollectionTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\PropertyAccess\PropertyAccess;

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
                'sort_by' => [
                    '[property]' => 'asc'
                ]
            ]
        );

        $this->assertEquals([0, 1, 2, 3], $this->extractPropertyValuesFromView('property', $form->createView()));
    }

    public function testCreateViewWithDescendingSorting()
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
                'sort_by' => [
                    '[property]' => 'desc'
                ]
            ]
        );

        $this->assertEquals([3, 2, 1, 0], $this->extractPropertyValuesFromView('property', $form->createView()));
    }

    private function extractPropertyValuesFromView($propertyName, $view)
    {
        return array_map(function ($view) use ($propertyName) {
            return $view->vars['data'][$propertyName];
        }, $view->children);
    }

    protected function getExtensions()
    {
        return [
            new PreloadedExtension(
                [],
                [
                    self::$collectionType => [
                        new CollectionTypeExtension(PropertyAccess::createPropertyAccessor())
                    ]
                ]
            )
        ];
    }
}
