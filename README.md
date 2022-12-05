# smart-serializer

It's the tiny library which can be used in any of PHP projects, however it was designed mainly to support Symfony projects (Symfony 6 and above).

## How to install
Call following command to add it to your project:

```bash
composer require smartgroup/smart-serializer
```

## How to use
This library is easy to use. For the entity or object which needs to be serialized, just annotate it's properties or methods with `Snapshot` annotation.

```php
class Photo
{
    #[Snapshot]
    private ?int $id;

    #[Snapshot]
    private string $locale = 'pl';

    #[Snapshot(isObject: true)]
    private PhotoGallery $photoGallery;

    #[Snapshot]
    private string $title;
    
    private string $slug;
}
```

Having the code above you will receive a serialized object, which contains fields which are annotated. To receive the serialized object just call:
```php
$serializedObject = Serializer::getSnapshot($photoObject, true);
```

## Snapshot annotation options
Annotating the object with Snapshot annotation, you have following options:
* isObject - field is an object which should be serialized as well 
* isDate - object is a date
* dateFormat - if object is a date you can specify the output format of date, by default it is 'Y-m-d H:i:s'
* fieldName - by default name of the value is the field name, but you can overwrite the name by putting value here
* isRoute - it will parse the value to extract the real route using symfony/router (it requires to pass symfony/router interface to getSnapshot method)
* isCollection - it will extract the value as the serialized collection
* translate - it will translate the value using symfony/translate (it requires to pass symfony/translate interface to getSnapshot method)

## Contributing

This project is maintained by a community of developers. Contributions are welcome and appreciated. You can find Jodit on GitHub; feel free to start an issue or create a pull requests:
https://github.com/mtulikowski/smart-serializer
