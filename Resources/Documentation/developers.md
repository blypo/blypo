# Developer Information

Some useful tips if you are about to use Blypo in your extension

[TOC]

### Autoloading

to enable full namespace autoloading support, make sure that your extension's `composer.json` exists and includes something similar to

```json
	"autoload": {
		"psr-4": {
			"YourName\\YourProject\\": "Classes"
		}
	}
```

### Cache location
Blypo lets blade cache the compiled views to `typo3temp/Blypo`.

### no_cache condition
During development the no_cache=1 get param is used a lot to avoid working on stale templates. When using no_cache=1 blypo will store the compiled views with a different name, leaving the original compiled view untouched. If, for whatever reason, you want to change this behavior, register a function at `$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['blypo']['sideStepCaching']` that determines whether or not to override the regular caching.

The default function in blypo's `ext_localconf.php` looks like this:

```php
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['blypo']['sideStepCaching'] = function(){
	return isset($_GET['no_cache']) && (int)$_GET['no_cache'] === 1;
};
```
