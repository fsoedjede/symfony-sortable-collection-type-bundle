<?php

namespace Fsv\SortableCollectionTypeBundle\Tests\DependencyInjection;

use Fsv\SortableCollectionTypeBundle\DependencyInjection\FsvSortableCollectionTypeExtension;
use Fsv\SortableCollectionTypeBundle\Form\Extension\CollectionTypeExtension;
use Fsv\SortableCollectionTypeBundle\FsvSortableCollectionTypeBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class FsvSortableCollectionTypeExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testDefaultConfiguration()
    {
        $container = $this->getRawContainer();
        $container->loadFromExtension('fsv_sortable_collection_type');
        $container->compile();

        $this->assertTrue($container->hasDefinition('fsv_sortable_collection_type.collection_type_extension'));
        $this->assertInstanceOf(CollectionTypeExtension::class, $container->get('fsv_sortable_collection_type.collection_type_extension'));
    }

    private function getRawContainer()
    {
        $container = new ContainerBuilder();
        $container->setDefinition('property_accessor', new Definition($this->getMock(PropertyAccessorInterface::class)));

        $extension = new FsvSortableCollectionTypeExtension();
        $container->registerExtension($extension);

        $bundle = new FsvSortableCollectionTypeBundle();
        $bundle->build($container);

        return $container;
    }
}
