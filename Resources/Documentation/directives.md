# Custom Directives

Unlike viewhelpers, directives are a core feature of blade. Directives are available in every view once registered and they work a little bit different than viewhelpers. **In general you should rather create viewhelpers than directives**. While directives have prettier syntax (`@yo`) they prove to be a little less user-friendly than viewhelpers and can be problematic when dealing with certain types of arguments, but decide for yourself.

Lets start by adding a `@hello` directive. First register a directive via hook in your `ext_localconf.php`

```php
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['blypo']['addDirective']['hello'] = 'Test\Testext\Directives\Hello';
```

The key will become the name of your directive, while the given class must be resolveable through your extension's autoload folder mapping (in your extension's composer.json autoload/psr-4 section) and extend `\AuM\Blypo\Directive\Directive` to hint at usage type during bootstrap (see below).

After clearing TYPO3's system caches, blypo will make the directive available to every view, layout and partial. Make sure that your class contains a public render method. Your class' render-method will receive two arguments upon being called:

- $expression - array of trimmed and splitted argument strings
- $expressionRaw - string of the original call

A directive has to return a string, because the Blade compiler replaces the directive call in the compiled template with the output of your class' `render` method. See an example below:

```php
namespace Test\Testext\Directives;

Class HelloDirective extends \AuM\Blypo\Directive\Directive{
  public function render($expression){
      return '<?php echo "Hello ".'.$expression[0].'; ?>';
  }
}
```

The render method returns a string of php code which is literally inserted into your compiled view and executed when your view is run. This may cause problems and unexpected results when certain types of variables are passed. For a more comprehensive and straight-forward way to add custom features to views have a look at viewhelpers.

Use the directive in your view, like in the example below:

```php
@hello(John) // prints 'Hello John'
```

If you really want to use directives be aware of how to use quotes and that your always have to return valid php code!