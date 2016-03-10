FsvSortableCollectionTypeBundle
===============

The bundle is used to sort the `FormView` objects in the `CollectionType`.
Sorting is based on the value of the specified property.
The property is determined by the property path in the format of the [Symfony PropertyAccess](https://github.com/symfony/property-access) component.

Installation
---------------
```
$ composer require fsv/sortable-collection-type-bundle
```

```php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Fsv\SortableCollectionTypeBundle\SortableCollectionTypeBundle(),
    );
}
```

Configuration
---------------
The bundle does not have any configurable parameters.

Usage
---------------
```php
// AppBundle\Form\Type\ExampleFormType.php

public function buildForm(FormBuilderInterface $builder, array $options)
{
    $builder->add('collection', CollectionType::class, [
        // ...
        'sort_by' => [
            'property' => 'asc'
        ]
    ]);
    // ...
}
```
