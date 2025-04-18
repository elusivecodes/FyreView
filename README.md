# FyreView

**FyreView** is a free, open-source template rendering library for *PHP*.


## Table Of Contents
- [Installation](#installation)
- [Basic Usage](#basic-usage)
- [Methods](#methods)
- [Layouts](#layouts)
- [Cells](#cells)
- [Elements](#elements)
- [Blocks](#blocks)
- [Template Locator](#template-locator)
- [Cell Registry](#cell-registry)
- [Helper Registry](#helper-registry)
- [Helpers](#helpers)
    - [CSP](#csp)
    - [Form](#form)
    - [Format](#format)
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


## Basic Usage

- `$templateLocator` is a [*TemplateLocator*](#template-locator).
- `$helperRegistry` is a [*HelperRegistry*](#helper-registry).
- `$cellRegistry` is a [*CellRegistry*](#cell-registry).
- `$request` is a [*ServerRequest*](https://github.com/elusivecodes/FyreServer#server-requests).

```php
$view = new View($templateLocator, $helperRegistry, $cellRegistry, $request);
```

Any dependencies will be injected automatically when loading from the [*Container*](https://github.com/elusivecodes/FyreContainer).

```php
$view = $container->use(View::class);
```


## Methods

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

**Get Request**

Get the [*ServerRequest*](https://github.com/elusivecodes/FyreServer#server-requests).

```php
$request = $view->getRequest();
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

Templates files must end in the extension `.php`, and must exist in one of the defined [*TemplateLocator*](#template-locator) paths.

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

Layout files must end in the extension `.php`, and must exist in a "*layouts*" folder in one of the defined [*TemplateLocator*](#template-locator) paths.


### Layouts

You can use layouts when rendering views by placing a `default.php` file in a *layouts* folder of one of the defined [*TemplateLocator*](#template-locator) paths. You can create multiple layouts, and specify the layout to use with the `setLayout` method above.

The rendered content is passed to the layout file via the `content` method of `$this`. Any other defined data is also passed to the layout.

```php
$this->content();
```


## Cells

Custom cells can be created by extending `\Fyre\View\Cell`, and suffixing the class name with "*Cell*".

**Cell**

Render a *Cell*.

- `$cell` is a string, and can either represent the cell name (implementing a `display` method) or in the format of "*Cell::method*".
- `$args` is an array of arguments that will be passed to the cell method, and will default to *[]*.

```php
echo $this->cell($cell, $args);
```

Cell classes must exist in a namespace that will be loaded by the [*CellRegistry*](#cell-registry).


## Elements

**Element**

Render an element.

- `$file` is a string representing the element file.
- `$data` is an array containing data to pass to the element, and will default to *[]*.

```php
echo $this->element($file, $data);
```

Element files must end in the extension `.php`, and must exist in an "*elements*" folder in one of the defined [*TemplateLocator*](#template-locator) paths.


## Blocks

**Append**

Append content to a block.

- `$name` is a string representing the block name.

```php
$this->append($name);
```

Any output until the block is ended will be appended to the block.

**Assign**

Assign content to a block.

- `$name` is a string representing the block name.
- `$content` is a string representing the content.

```php
$this->assign($name, $content);
```

**End**

End a block.

```php
$this->end();
```

**Fetch**

Fetch a block.

- `$name` is a string representing the block name.
- `$default` is a string representing the default value, and will default to "".

```php
$block = $this->fetch($name, $default);
```

**Prepend**

Prepend content to a block.

- `$name` is a string representing the block name.

```php
$this->prepend($name);
```

Any output until the block is ended will be prepended to the block.

**Reset**

Reset content of a block.

- `$name` is a string representing the block name.

```php
$this->reset($name);
```

**Start**

- `$name` is a string representing the block name.

Start content for a block.

```php
$this->start($name);
```


## Template Locator

```php
use Fyre\View\TemplateLocator;
```

```php
$templateLocator = new TemplateLocator();
```

**Autoloading**

It is recommended to bind the *TemplateLocator* to the [*Container*](https://github.com/elusivecodes/FyreContainer) as a singleton.

```php
$container->singleton(TemplateLocator::class);
```

### Template Locator Methods

**Add Path**

Add a path for loading templates.

- `$path` is a string representing the path.

```php
$templateLocator->addPath($path);
```

**Get Paths**

Get the paths.

```php
$paths = $templateLocator->getPaths();
```

**Locate**

Find a file in paths.

- `$file` is a string representing the file name.
- `$folder` is a string representing the folder name, and will default to "".

```php
$filePath = $templateLocator->findFile($file, $folder);
```

**Remove Path**

- `$path` is a string representing the path.

```php
$templateLocator->removePath($path);
```

### Template Locator Static Methods

**Normalize**

Normalize a template file name.

- `$string` is a string representing the file name.

```php
$normalized = TemplateLocator::normalize($string);
```


## Cell Registry

```php
use Fyre\View\CellRegistry;
```

- `$container` is a [*Container*](https://github.com/elusivecodes/FyreContainer).

```php
$cellRegistry = new CellRegistry($container);
```

**Autoloading**

It is recommended to bind the *CellRegistry* to the [*Container*](https://github.com/elusivecodes/FyreContainer) as a singleton.

```php
$container->singleton(CellRegistry::class);
```

Any dependencies will be injected automatically when loading from the [*Container*](https://github.com/elusivecodes/FyreContainer).

```php
$cellRegistry = $container->use(CellRegistry::class);
```

### Cell Registry Methods

**Add Namespace**

Add a namespace for automatically loading cells.

- `$namespace` is a string representing the namespace.

```php
$cellRegistry->addNamespace($namespace);
```

**Build**

Build a cell.

- `$name` is a string representing the cell name.
- `$view` is a *View*.
- `$options` is an array containing cell options.

```php
$cell = $cellRegistry->build($name, $view, $options);
```

**Clear**

Clear all namespaces and cells.

```php
$cellRegistry->clear();
```

**Find**

Find a cell class.

- `$name` is a string representing the cell name.

```php
$className = $cellRegistry->find($name);
```

**Get Namespaces**

Get the namespaces.

```php
$namespaces = $cellRegistry->getNamespaces();
```

**Has Namespace**

Check if a namespace exists.

- `$namespace` is a string representing the namespace.

```php
$hasNamespace = $cellRegistry->hasNamespace($namespace);
```

**Remove Namespace**

Remove a namespace.

- `$namespace` is a string representing the namespace.

```php
$cellRegistry->removeNamespace($namespace);
```


## Helper Registry

```php
use Fyre\View\HelperRegistry;
```

- `$container` is a [*Container*](https://github.com/elusivecodes/FyreContainer).

```php
$helperRegistry = new HelperRegistry($container);
```

**Autoloading**

It is recommended to bind the *HelperRegistry* to the [*Container*](https://github.com/elusivecodes/FyreContainer) as a singleton.

```php
$container->singleton(HelperRegistry::class);
```

Any dependencies will be injected automatically when loading from the [*Container*](https://github.com/elusivecodes/FyreContainer).

```php
$helperRegistry = $container->use(HelperRegistry::class);
```

### Helper Registry Methods

**Add Namespace**

Add a namespace for automatically loading helpers.

- `$namespace` is a string representing the namespace.

```php
$helperRegistry->addNamespace($namespace);
```

**Build**

Build a helper.

- `$name` is a string representing the helper name.
- `$view` is a *View*.
- `$options` is an array containing helper options.

```php
$helper = $helperRegistry->build($name, $view, $options);
```

**Clear**

Clear all namespaces and helpers.

```php
$helperRegistry->clear();
```

**Find**

Find a helper class.

- `$name` is a string representing the helper name.

```php
$className = $helperRegistry->find($name);
```

**Get Namespaces**

Get the namespaces.

```php
$namespaces = $helperRegistry->getNamespaces();
```

**Has Namespace**

Check if a namespace exists.

- `$namespace` is a string representing the namespace.

```php
$hasNamespace = $helperRegistry->hasNamespace($namespace);
```

**Remove Namespace**

Remove a namespace.

- `$namespace` is a string representing the namespace.

```php
$helperRegistry->removeNamespace($namespace);
```


## Helpers

Helpers can be used inside of templates or elements using the class name as a property of `$this`.

```php
$helper = $this->MyHelper;
```

Alternatively, you can load a helper with configuration options using the `loadHelper` method of the *View*.

Custom helpers can be created by extending `\Fyre\View\Helper`, suffixing the class name with "*Helper*", and ensuring the `__construct` method accepts *View* as the argument (and optionally an `$options` array as the second parameter).

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

**Button**

Render a button element.

- `$content` is a string representing the button content.
- `$options` is an array of options for rendering the button.

```php
$button = $this->Form->button($content, $options);
```

By default, the button content will be HTML escaped. To disable this, set the `escape` value to *false* in the `options` array.

All other `options` will be created as attributes on the button element.

**Close**

Render a form close tag.

```php
$close = $this->Form->close();
```

**Fieldset Close**

Render a fieldset close tag.

```php
$fieldsetClose = $this->Form->fieldsetClose();
```

**Fieldset Open**

Render a fieldset open tag.

- `$options` is an array of options for rendering the fieldset.

```php
$fieldset = $this->Form->fieldsetOpen($options);
```

All `options` will be created as attributes on the fieldset element.

**Input**

Render an input element.

- `$key` is a string representing the field key, using dot notation.
- `$options` is an array of options for rendering the label.

```php
$input = $this->Form->input($key, $options);
```

All `options` will be created as attributes on the input element.

- The default `id` and `name` attributes will be converted from the field key.
- The input `type` and other default attributes will be determined from the [*TableSchema*](https://github.com/elusivecodes/FyreSchema#table-schemas) and [*Model Validation*](https://github.com/elusivecodes/FyreORM#validation).
- The default value will be retrieved from the [*ServerRequest*](https://github.com/elusivecodes/FyreServer#server-requests) `$_POST` data or form context.
    - If the form was opened in an [*Entity*](https://github.com/elusivecodes/FyreEntity) context, the [*Entity*](https://github.com/elusivecodes/FyreEntity) and the [*TableSchema*](https://github.com/elusivecodes/FyreSchema#table-schemas) will also be used.
- Select options can be specified using the `options` key.
- Checkboxes and radio inputs can be marked as checked by setting the `checked` option to *true*.
- Multiple select menus will (by default) render a hidden field with an empty value. This can be disabled by setting the `hiddenField` option to *false*.
- Checkboxes will (by default) render a hidden field with the value "*0*". This can be disabled by setting the `hiddenField` option to *false*.

You can also use the following helper methods to generate specific input type fields.

```php
$input = $this->Form->checkbox($key, $options);
$input = $this->Form->color($key, $options);
$input = $this->Form->date($key, $options);
$input = $this->Form->datetime($key, $options);
$input = $this->Form->email($key, $options);
$input = $this->Form->file($key, $options);
$input = $this->Form->hidden($key, $options);
$input = $this->Form->image($key, $options);
$input = $this->Form->month($key, $options);
$input = $this->Form->number($key, $options);
$input = $this->Form->password($key, $options);
$input = $this->Form->radio($key, $options);
$input = $this->Form->range($key, $options);
$input = $this->Form->reset($key, $options);
$input = $this->Form->search($key, $options);
$input = $this->Form->select($key, $options);
$input = $this->Form->selectMulti($key, $options);
$input = $this->Form->submit($key, $options);
$input = $this->Form->tel($key, $options);
$input = $this->Form->text($key, $options);
$input = $this->Form->time($key, $options);
$input = $this->Form->url($key, $options);
$input = $this->Form->week($key, $options);
```

**Label**

Render a label element.

- `$key` is a string representing the field key.
- `$options` is an array of options for rendering the label.

```php
$label = $this->Form->label($key, $options);
```

The label text will be retrieved from the [*Lang*](https://github.com/elusivecodes/FyreLang) using the `Field.{field_name}` key or converted from the field name. You can also set custom label text by setting the `text` option.

By default, the label content will be HTML escaped. To disable this, set the `escape` value to *false* in the `options` array.

All other `options` will be created as attributes on the label element. The default `for` attribute will be converted from the field key.

**Legend**

Render a legend element.

- `$content` is a string representing the legend content.
- `$options` is an array of options for rendering the legend.

```php
$legend = $this->Form->legend($content, $options);
```

By default, the legend content will be HTML escaped. To disable this, set the `escape` value to *false* in the `options` array.

All other `options` will be created as attributes on the legend element.

**Open**

Render a form open tag.

- `$item` is an [*Entity*](https://github.com/elusivecodes/FyreEntity) or other (supported) object representing the form context, and will default to *null*
- `$options` is an array of options for rendering the form.

```php
$open = $this->Form->open($item, $options);
```

All `options` will be created as attributes on the form element.

**Open Multipart**

Render a multipart form open tag.

- `$item` is an array or object representing the form context, and will default to *null*
- `$options` is an array of options for rendering the form.

```php
$open = $this->Form->openMultipart($item, $options);
```

All `options` will be created as attributes on the form element.


### Format

The format helper provides a convenient wrapper for [*Formatter*](https://github.com/elusivecodes/FyreFormatter) methods.


### Url

The URL helper provides methods for generating [*Router*](https://github.com/elusivecodes/FyreRouter) links.

**Link**

Generate an anchor link for a destination.

- `$content` is a string representing the link content.
- `$options` is an array containing options.
    - `escape` is a boolean indicating whether to escape the link content, and will default to *true*.
    - `fullBase` is a boolean indicating whether to use the full base URI, and will default to *false*.

```php
$link = $this->Url->link($content, $options);
```

**Path**

Generate a url for a relative path.

- `$path` is a string representing the file path.
- `$options` is an array containing options.
    - `fullBase` is a boolean indicating whether to use the full base URI, and will default to *false*.

```php
$url = $this->Url->path($path, $options);
```

**To**

Generate a url for a named route.

- `$name` is a string representing the route alias.
- `$arguments` is an array containing the route arguments.
    - `?` is an array containing route query parameters.
    - `#` is a string representing the fragment component of the URI.
- `$options` is an array containing options.
    - `fullBase` is a boolean indicating whether to use the full base URI, and will default to *false*.

```php
$url = $this->Url->to($destination, $options);
```