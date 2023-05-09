# EasyAdmin sortable bundle

This bundle provides a convenient way to make your entities sortable as well as a drag-and-drop in the
EasyAdmin list view.

The javascript and css was copied from [treetop1500/easyadmin-dragndrop-sort
](https://github.com/treetop1500/easyadmin-dragndrop-sort) with some minor improvements.


Updated in 2023 for PHP 8.2 / Symfony 6 / EA 4.

# Installation

Install the bundle using composer:

```bash
$ composer req penguinus/ea-sortable-bundle
```

Add the bundle routing to `config/routes.yaml`:

```yaml
ea-sortable:
  resource: '@OrkestraEaSortableBundle/Controller/'
  type: annotation
```

# Usage

### Using the sortable trait
Add the `Orkestra\EaSortable\SortableTrait` trait to your entity.

### Without sortable trait
If you already have a table and sorting position field name is not `position`, configure it manually. 

# Configuration
### Sample entity configuration

```php
class SomeEntity
{
...
    #[ORM\Column(type: Types::SMALLINT)]
    #[Gedmo\SortablePosition]
    private ?int $position = null;
    
    #[Gedmo\SortableGroup]
    private ?Syllabus $group = null;
...
}
```



### Sample crud controller configuration

```php
class SomeCrudController extends AbstractCrudController
{
...
    public function configureFields(string $pageName): iterable
    {
        yield IntegerField::new('position')->setLabel('Order')->setSortable(false);
        yield TextField::new('name')->setSortable(false);
        yield TextEditorField::new('description')->setSortable(false);
    }

    public function configureActions(Actions $actions): Actions
    {
        $actionSort = Action::new('sort')
            ->setTemplatePath('@OrkestraEaSortable/ea-sortable.html.twig')
            ->linkToRoute('easyadmin.sortable.sort', ['property' => 'position', 'fqcn' => SomeEntity::class])
            ->createAsGlobalAction()
        ;
        return $actions
            ->add(Crud::PAGE_INDEX, $actionSort)
        ;
    }
...
}
```

Some notes about the configuration:

1. you need to provide the custom `sort` action in order to enable the drag-and-drop functionality
2. sorting must be disabled on all other list fields (there is no way to do this globally in EasyAdmin)
