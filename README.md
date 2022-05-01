# FyreView

**FyreView** is a free, template rendering library for *PHP*.


## Table Of Contents
- [Installation](#installation)
- [Methods](#methods)
- [Paths](#paths)
- [Helpers](#helpers)
    - [CSP](#csp)
    - [Form](#form)
    - [Url](#url)



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

Helpers can be used inside of templates or elements using the class name as a property of `$this`.

```php
$this->MyHelper->method();
```

Custom helpers can be created by extending `\Fyre\View\Helper`, ensuring the `__construct` method accepts *View* as the argument.

**Add Namespace**

Add a namespace for automatically loading helpers.

- `$namespace` is a string representing the namespace.

```php
View::addNamespace($namespace);
```

### CSP

The CSP helper allows an easy way to generate nonces, and automatically add them to your [*CSP*](https://github.com/elusivecodes/FyreCSP) policies.

**Script Nonce**

Generate a script nonce.

```php
$nonce = $this->CSP->scriptNonce();
```

**Style Nonce**

Generate a style nonce.

```php
$nonce = $this->CSP->styleNonce();
```

### Form

The form helper provides a convenient wrapper for [*FormBuilder*](https://github.com/elusivecodes/FyreFormBuilder) methods, and includes the following additional methods.

**Csrf**

Render a CSRF token input element.

```php
$input = $this->Form->csrf();
```

### Url

The URL helper provides methods for generating [*Router*](https://github.com/elusivecodes/FyreRouter) links.

**Link**

Generate an anchor link for a destination.

- `$content` is a string representing the link content.
- `$destination` is a string or array containing the destination.
- `$options` is an array containing options.
    - `escape` is a boolean indicating whether to escape the link content, and will default to *true*.
    - `fullBase` is a boolean indicating whether to use the full base URI, and will default to *false*.

```php
$link = $this->Url->link($content, $destination, $options);
```

**To**

Generate a url for a destination.

- `$destination` is a string or array containing the destination.
- `$options` is an array containing options.
    - `fullBase` is a boolean indicating whether to use the full base URI, and will default to *false*.

```php
$url = $this->Url->to($destination, $options);
```