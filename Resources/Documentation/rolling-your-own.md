# Rolling your own viewhelpers

Custom viewhelpers are blypos biggest single addition to blade and a great way to keep utility code away from extension code and add functionality to your views where using plain php is redundant or not an viable option.

Creating and using viewhelpers is very straight-forward by mapping them to a dynamically created class/namespace using blypo's built-in @namespace directive (See below).

[TOC]

### Building a Viewhelper

Viewhelper classes have to have a public render method and must extend `AuM\Blypo\ViewHelper\ViewHelper` as shown below with a small example of how to build a viewhelper:

```php
namespace Test\Testext\ViewHelper;

Class FormatUpper extends \AuM\Blypo\ViewHelper\ViewHelper{

  public function render(){
    $str = $this-arguments('str');
    echo strtoupper($str);
  }

}
```
In the example above, the viewhelper transforms a string to uppercase. In practice, with Blypo you wouldn't need this kind of viewhelper, as you can simply write `{{ strtoupper('hello world') }}` in your template to execute the call with virtually zero overhead. However, this is a small example of how viewhelpers work.

As shown above, all arguments passed to the viewhelper are made available through `$this->arguments`.

### Using a custom viewhelper

To use the viewhelper in one of your templates, simply put the following anywhere in your template:

```php
@namespace(\Test\Testext\ViewHelper,test)
```

The `@namespace` directive maps your namespace (`\Test\Testext\ViewHelper`) to a namespace inside the blade template (`test`). Every viewhelper living inside the mapped namespace is now available for your template as shown below:

```php
{{ test::formatUpper(['str' => 'hello world']) }}
```

The classname of the viewhelper should follow convention by using a first upper letter, inside the template the lower camelcase variant should be used.

### Registering default viewhelpers

If you want to provide viewhelpers for every template without including them everywhere explicitly, just put a line into your `ext_localconf.php` like so:

```php
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['blypo']['defaultViewHelpers']['test'] = '\Test\Testext\ViewHelper';
```

Now all viewhelpers inside that namespace will be usable in every template!

### Inheritance, Arguments and Properties

Every viewhelper must at some point extend `\AuM\Blypo\ViewHelper\ViewHelper`!

Every viewhelper has access to `AuM\Blypo\Rendering\RenderingContext` via `static::$renderingContext` and `TYPO3\CMS\Extbase\Mvc\Controller\ControllerContext` via `static::$controllerContext` which represent the equally named objects in fluid viewhelper.

More information about [RenderingContext](https://typo3.org/api/typo3cms/class_t_y_p_o3_1_1_c_m_s_1_1_fluid_1_1_core_1_1_rendering_1_1_rendering_context.html) and [ControllerContext](https://typo3.org/api/typo3cms/class_t_y_p_o3_1_1_c_m_s_1_1_extbase_1_1_mvc_1_1_controller_1_1_controller_context.html).

Arguments are either passed as an array which keys may then accessed through `$this->arguments('key')` or as a single value when the viewhelper only uses one variable.
