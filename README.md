# FyreView

**FyreView** is a free, template rendering library for *PHP*.


## Table Of Contents
- [Installation](#installation)
- [Methods](#methods)
- [Paths](#paths)
- [Helpers](#helpers)
    - [Using Helpers](#using-helpers)



## Installation

**Using Composer**

```
composer require fyre/view
```

In PHP:

```php
use Fyre\View\View;
```


## Methods

```php
$view = new View();
```

**Element**

Render an element.

- `$file` is a string representing the element file.
- `$data` is an array containing data to pass to the element, and will default to *[]*.

```php
echo $view->element($file, $data);
```

Element files must end in the extension "*.php*", and must exist in an "*elements*" folder in one of the defined paths.

**Get Data**

Get the view data.

```php
$data = $view->getData();
```

**Render**

Render a template.

- `$file` is a string representing the template file.

```php
echo $view->render($file);
```

Templates files must end in the extension "*.php*", and must exist in one of the defined paths.

**Set Data**

Set view data.

- `$data` is an array containing data to pass to the template.

```php
$view->setData($data);
```


## Paths

Templates and elements are loaded by searching available paths.

**Add Path**

Add a path for loading templates.

- `$path` is a string representing the path.

```php
View::addPath($path);
```


## Helpers

Custom helpers can be created by extending `\Fyre\View\Helper`, ensuring the `__construct` method accepts *View* as the argument.

**Add Namespace**

Add a namespace for automatically loading helpers.

- `$namespace` is a string representing the namespace.

```php
View::addNamespace($namespace);
```

### Using Helpers

Helpers can be used inside of templates or elements using the class name as a property of `$this`.

```php
$this->MyHelper->method();
```