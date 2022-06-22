# FyreView

**FyreView** is a free, template rendering library for *PHP*.


## Table Of Contents
- [Installation](#installation)
- [Methods](#methods)
- [Layouts](#layouts)
- [Paths](#paths)
- [Helper Registry](#helper-registry)
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

Element files must end in the extension `.php`, and must exist in an "*elements*" folder in one of the defined paths.

**Get Data**

Get the view data.

```php
$data = $view->getData();
```

**Get Layout**

Get the layout.

```php
$layout = $view->getLayout();
```

**Load Helper**

Load a [*Helper*](#helpers).

- `$name` is a string representing the helper name.
- `$options` is an array containing helper options.

```php
$helper = $view->loadHelper($name, $options);
```

**Render**

Render a template.

- `$file` is a string representing the template file.

```php
echo $view->render($file);
```

Templates files must end in the extension `.php`, and must exist in one of the defined paths.

**Set Data**

Set view data.

- `$data` is an array containing data to pass to the template.

```php
$view->setData($data);
```

**Set Layout**

Set the layout.

- `$layout` is a string representing the layout file.

```php
$view->setLayout($layout);
```

Layout files must end in the extension `.php`, and must exist in a "*layouts*" folder in one of the defined paths.


## Layouts

You can use layouts when rendering views by placing a `default.php` file in a *layouts* folder of one of the defined paths. You can create multiple layouts, and specify the layout to use with the `setLayout` method above.

The rendered content is passed to the layout file via the `content` method of `$this`. Any other defined data is also passed to the layout.

```php
$this->content();
```


## Paths

Layouts, templates and elements are loaded by searching available paths.

**Add Path**

Add a path for loading templates.

- `$path` is a string representing the path.

```php
View::addPath($path);
```


## Helper Registry

```php
use Fyre\View\HelperRegistry;
```

**Add Namespace**

Add a namespace for automatically loading helpers.

- `$namespace` is a string representing the namespace.

```php
HelperRegistry::addNamespace($namespace);
```

**Clear**

Clear all namespaces and helpers.

```php
HelperRegistry::clear();
```

**Find**

Find a helper class.

- `$name` is a string representing the helper name.

```php
$className = HelperRegistry::find($name);
```

**Load**

Load a helper.

- `$name` is a string representing the helper name.
- `$view` is a *View*.
- `$options` is an array containing helper options.

```php
$helper = HelperRegistry::load($name, $view, $options);
```


## Helpers

Helpers can be used inside of templates or elements using the class name as a property of `$this`.

```php
$helper = $this->MyHelper;
```

Alternatively, you can load a helper with configuration options using the `loadHelper` method of the *View*.

Custom helpers can be created by extending `\Fyre\View\Helper`, ensuring the `__construct` method accepts *View* as the argument.

**Get Config**

Get the configuration options.

```php
$config = $helper->getConfig();
```

**Get View**

Get the *View*.

```php
$view = $helper->getView();
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


### Format

The form helper provides a convenient wrapper for [*Formatter*](https://github.com/elusivecodes/FyreFormatter) methods.


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