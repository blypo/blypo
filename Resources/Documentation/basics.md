# Using Blypo

Replacing fluid with blade in your TYPO3 setup is pretty easy and straight-forward. Blypo replaces fluid for typoscript-based objects and can be used in your own extension as well.

[TOC]

### page template

Using blade instead of fluid for your page templates just set up your pagerender to
use blade by setting it to `BLYPO_TEMPLATE` as demonstrated below:

```typoscript
page = PAGE
page {
  10 = BLYPO_TEMPLATE
  10 {
    paths {
      views = EXT:yourext/Resources/Private/Views
    }

    file = Site.index

    #global vars
    variables {
        content < styles.content.get
        content.select.where = colPos = 0
    }

    #global settings
    settings {
        homePid = 2
    }
  }
}
```

### naming conventions for files

As shown in the example above blade comes with its own way of resolving files on disk. Views will
be searched for in the folder configured in the `path.views` property and will be resolved like this

```
Site.default
```
is expanded to
```
/Site/default.blade.php
```
and finally resolved to
```
yourext/Resources/Private/Views/Site/default.blade.php
```
Dots are automatically resolved to slashes while the leading slash is prepended and the file extension `.blade.php`
 appended.

### gridelements

As mentioned above, Blypo should work with almost anything that uses typoscript object based rendering. For completeness sake, here is an example on how to make gridelements use a blade template:
```typoscript
tt_content.gridelements_pi1.20.10.setup.example < lib.gridelements.defaultGridSetup
tt_content.gridelements_pi1.20.10.setup.example {
	cObject = BLYPO_TEMPLATE
	cObject {
		paths {
			views = EXT:yourext/Resources/Private/Views
		}
		file = example
	}
}
```
The example would resolve a file called
```
yourext/Resources/Private/Views/example.blade.php
```

### custom extensions with extbase

To use blade in your own extension simply extend the custom BlypoController instead of the usual Extbase ActionController, like so
```php
class YourController extends \AuM\Blypo\BladeController
```

Below is an example Controller that uses blade instead of fluid. The `$view` property and its `assign` and `assignMultiple` methods work the same as in fluid.
```php
namespace Test\Testext\Controller;

class TestController extends \AuM\Blypo\BladeController {
  public function listAction(){
    $this->view->assign('test','Testvariable');
    $this->view->assignMultiple([
        'foo' => 'bar',
        'someVar' => 'test'
    ]);
  }
}
```

### accessing variables

Just like when using fluid, you can simply access extbase objects with the dot-notation which is then
converted to plain php calls using the getters from the models.

For example

```php
$user.company.name
```

internally is expanded to

```php
$user->getCompany()->getName()
```

Likewise, arrays can be accessed by using dot notation:

```php
$user.company.name
```

is internally expanded to

```php
$user['company']['name']
```
