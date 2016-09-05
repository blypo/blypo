# Viewhelpers

Blypo provides fluid's essential viewhelpers, used for various simple tasks in your templates, as well as a few own viewhelpers. Also, Blypo makes it easy to implement your viewhelpers and to make them available to selected views or all views at once.

[TOC]

All of Blypo's viewhelpers are located in the `b` namespace, analog to fluids `f` namespace, which means they are called through `b::viewHelperName`.

Here is a list of all viewhelpers currently implemented by Blypo. Feel free to create your own viewhelper and push them to our Github Repo if they are usefull for everybody. Not all fluid viewhelpers have been recreated because many of them are superseded by blade or native php features.

## Built-in Fluid equivalent Viewhelpers

The following viewhelpers are largely based on their fluid-equivalents, so if you are familiar
with a fluid-viewhelper of the same name there should be no suprises

### Link/Page

Creates an url for given page uid or typolink compatible input

```php
{{ b::linkPage(['pageUid' => 1]) }}
```
**Arguments**

|Name|Type|Description|
|---|---|
|absolute|boolean|If set, the URI of the rendered link is absolute|
|additionalParams|array|query parameters to be attached to the resulting URI|
|addQueryString|boolean|If set, the current query parameters will be kept in the URI|
|addQueryStringMethod|string|Set which parameters will be kept. Only active if $addQueryString = TRUE|
|argumentsToBeExcludedFromQueryString|array|arguments to be removed from the URI. Only active if $addQueryString = TRUE|
|linkAccessRestrictedPages|boolean|If set, links pointing to access restricted pages will still link to the page even though the page cannot be accessed.|
|noCache|boolean|set this to disable caching for the target page. You should not need this.|
|noCacheHash|boolean|set this to suppress the cHash query parameter created by TypoLink. You should not need this.|
|pageUid|anySimpleType|target page. See TypoLink destination|
|pageType|integer|type of the target page. See typolink.parameter|
|section|string|the anchor to be added to the URI|

### Link/Action

Creates an url to extension action from given arguments

```php
{{ b::linkAction(['pageUid' => 1, 'action' => 'list', 'controller' => 'Test', 'extensionName' => 'Testext']) }}
```
**Arguments**

|Name|Type|Description|
|---|---|
|absolute|boolean|If set, the URI of the rendered link is absolute|
|action|string|Target action|
|additionalParams|array|query parameters to be attached to the resulting URI|
|addQueryString|boolean|If set, the current query parameters will be kept in the URI|
|addQueryStringMethod|string|Set which parameters will be kept. Only active if $addQueryString = TRUE|
|arguments|array|Arguments|
|argumentsToBeExcludedFromQueryString|array|arguments to be removed from the URI. Only active if $addQueryString = TRUE|
|controller|string|Target controller. If NULL current controllerName is used|
|extensionName|string|Target Extension Name (without "tx_" prefix and no underscores). If NULL the current extension name is used|
|format|string|The requested format, e.g. ".html|
|linkAccessRestrictedPages|boolean|If set, links pointing to access restricted pages will still link to the page even though the page cannot be accessed.|
|noCache|boolean|set this to disable caching for the target page. You should not need this.|
|noCacheHash|boolean|set this to suppress the cHash query parameter created by TypoLink. You should not need this.|
|pluginName|string|Target plugin. If empty, the current plugin name is used|
|pageType|integer|type of the target page. See typolink.parameter|
|pageUid|anySimpleType|target page. See TypoLink destination|
|section|string|the anchor to be added to the URI|

### Link/Email

Creates a mailto:-link through typolink, respecting TYPO3's spamProtectEmailAddresses-settings

```php
{{ b::linkEmail(['email' => 'info@example.com']) }}
```

**Arguments**


|Name|Type|Description|
|---|---|
|email|string|The email address|

### Link/Image

Resizes Image to given dimensions, aspect ratio using core features and returns the file's path.

```php
{{ b::linkImage(['src' => 'fileadmin/Img/blypo.png', 'size' => '150 , 100' ]) }}
```

**Arguments**


|Name|Type|Description|
|---|---|
|absolute|integer|Force absolute URL|
|crop|integer|overrule cropping of image (setting to FALSE disables the cropping set in FileReference)|
|image|Fal Object|a FAL object|
|reference|integer|given src argument is a sysfilereference record|
|sizes|string|Shortform for width and height, comma separated ('100,100'). If only one value, the output is a square|
|sizesMin|string|Shortform for minWidth and minHeight, comma separated ('100,100'). If only one value, the output is a square|
|sizesMax|string|Shortform for maxWidth and maxHeight, comma separated ('100,100'). If only one value, the output is a square|
|src|string|a path to a file, a combined FAL identifier or an uid (int).|
|height|string|height of the image. This can be a numeric value representing the fixed height of the image in pixels. But you can also perform simple calculations by adding "m" or "c" to the value. See imgResource.width for possible options.|
|width|string|width of the image. This can be a numeric value representing the fixed width of the image in pixels. But you can also perform simple calculations by adding "m" or "c" to the value. See imgResource.width for possible options.|
|minHeight|integer|minimum height of the image|
|minWidth|integer|minimum width of the image|
|maxHeight|integer|maximum height of the image|
|maxWidth|integer|maximum width of the image|

### typoLink

A viewhelper that gives a direct interface to typolink and parses "typolink"-strings

```php

{!! b::typoLink(['parameter' => '7 _blank - "testtitle with whitespace" &X=y']) !!}
```

**Arguments**


|Name|Type|Description|
|---|---|
|additionalParams|array|array of params to the link|
|additionalAttributes|array|array of attributes added to the link|
|class|string|Adds classnames to the link|
|parameter|string|stdWrap.typolink style parameter string|
|target|string|target of the link|
|title|string|title of the link|

### translate

Translate a key from locallang-xliff. The files used are loaded from `Resources/Private/Language/` of the given extension as usual.

```php
{{ b::translate(['key' => 'somekey','extensionName' => 'TestExt']) }}
```

**Arguments**


|Name|Type|Description|
|---|---|
|arguments|array|Arguments to be replaced in the resulting string|
|default|string|If the given locallang key could not be found, this value is used. If this argument is not set, child nodes will be used to render the default|
|extensionName|string|UpperCamelCased extension key (for example BlogExample)|
|htmlEscape|boolean|TRUE if the result should be htmlescaped. This won't have an effect for the default value|
|id|string|Translation Key compatible to TYPO3 Flow|
|key|string|Translation Key|

### Format/Bytes

Formats an integer with a byte count into human-readable form.

```php
{{ b::formatBytes(['value' => 100000]) }}
```

**Arguments**

|Name|Type|Description|
|---|---|
|decimalSeparator|string|The decimal point character|
|decimals|integer|The number of digits after the decimal point|
|thousandsSeparator|string|The character for grouping the thousand digits|
|value|integer|The incoming data to convert, or NULL if VH children should be used|

### Format/Crop

Use this Viewhelper to crop the given text

```php
{{ b::formatCrop(['str' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed']) }}
```

**Arguments**

|Name|Type|Description|
|---|---|
|append|string|What to append, if truncation happened|
|maxCharacters|integer|Place where to truncate the string|
|str|string|The string to crop|
|respectHtml|boolean|If TRUE the cropped string will respect HTML tags and entities. Technically that means, that cropHTML() is called rather than crop()|
|respectWordBoundaries|boolean|If TRUE and division is in the middle of a word, the remains of that word is removed.|

### Format/Currency

Formats a given float to a currency representation.
```php
{{ b::formatCurrency(['value' => 123.456]) }}
```

**Arguments**


|Name|Type|Description|
|---|---|
|currencySign|string|(optional) The currency sign, eg $ or .|
|decimals|integer|(optional) Set decimals places.|
|decimalSeparator|string|The separator for the decimal point.|
|prependCurrency|boolean|(optional) Select if the curreny sign should be prepended|
|separateCurrency|boolean|(optional) Separate the currency sign from the number by a single space, defaults to true due to backwards compatibility|
|thousandsSeparator|string|(optional) The thousands separator.|
|value|integer|The incoming data to convert|

### Format/Html

Renders a string by passing it to a TYPO3 parseFunc. You can either specify a path to the TypoScript setting or set the parseFunc options directly.

By default `lib.parseFunc_RTE` is used to parse the string.

```php
# note that you need {!! !!} for raw output of html! See Documentation/Blade for more info
{!! b::formatHtml(['value' => 'some test output']) !!}

#short way, because there is only one argument
{!! b::formatHtml('some test output') !!}
```
**Arguments**


|Name|Type|Description|
|---|---|
|value|string|The string you want to parse to html output|
|parseFunc|string|path to TypoScript parseFunc setup.|

### cObject

This ViewHelper renders CObjects from the global TypoScript configuration.

```php
{{ b::cObject(['tspath' => 'lib.test']) }}
```

|Name|Type|Description|
|---|---|
|currentValueKey|string| |
|data|mixed|the data to be used for rendering the cObject. Can be an object, array or string. If this argument is not set, child nodes will be used|
|table|string| |
|tspath|string|the TypoScript setup path of the TypoScript object to render|

### debug

This ViewHelper generates a HTML dump of the given variable

```php
{{ b::debug(['someVar' => 'Var1']) }}

// for getting all availabled variables in the view use $_all like below
{{ b::debug($_all) }}
```
**Arguments**


|Name|Type|Description|
|---|---|
|ansiColors|boolean|If TRUE, ANSI color codes is added to the plaintext output, if FALSE (default) the plaintext debug output not colored.|
|blacklistedClassNames|array|An array of class names (RegEx) to be filtered. Default is an array of some common class names.|
|blacklistedPropertyNames|array|An array of property names and/or array keys (RegEx) to be filtered. Default is an array of some common property names.|
|inline|boolean|if TRUE, the dump is rendered at the position of the tag. If FALSE (default), the dump is displayed at the top of the page.|
|maxDepth|integer|Sets the max recursion depth of the dump (defaults to 8). De- or increase the number according to your needs and memory limit.|
|plainText|boolean|If TRUE, the dump is in plain text, if FALSE the debug output is in HTML format.|
|title|string|optional custom title for the debug output|

## Built-in Blypo Viewhelpers

Some viewhelpers that offer extra functionality

### pagination

Creates a pagination with given parameters. Instead of wrapping your output with a viewhelper
and losing almost all control over how the output is generated etc, you may simply place this
viewhelper where a pagination feels best for you!

```php
{{ b::pagination(['total' => 52]) }}
```
**Arguments**

|Name|Type|Description|
|---|---|
|itemTag|string|Default: li, the tag in which each item is rendered|
|params|string|Parameter which should be added to typolink (eg. &newsaction=List)|
|perPage|integer|Default: 10, how many items per Page|
|range|integer|Default: 4, how many pages should be visible at once.|
|wrapper|string|Default: ul, the tag which wraps the items|
|total|integer|total amound of items|

### Image

Resizes a given image (if required) and renders the respective img tag. Uses core features.

```php
{!! b::image(['src' => 'fileadmin/Img/blypo.png', 'size' => '150 , 100' ]) !!}
```

**Arguments**


|Name|Type|Description|
|---|---|
|absolute|integer|Force absolute URL|
|attributes|array|Attributes added to the image tag|
|crop|integer|overrule cropping of image (setting to FALSE disables the cropping set in FileReference)|
|image|Fal Object|a FAL object|
|reference|integer|given src argument is a sysfilereference record|
|sizes|string|Shortform for width and height, comma separated ('100,100'). If only one value, the output is a square|
|sizesMin|string|Shortform for minWidth and minHeight, comma separated ('100,100'). If only one value, the output is a square|
|sizesMax|string|Shortform for maxWidth and maxHeight, comma separated ('100,100'). If only one value, the output is a square|
|src|string|a path to a file, a combined FAL identifier or an uid (int).|
|height|string|height of the image. This can be a numeric value representing the fixed height of the image in pixels. But you can also perform simple calculations by adding "m" or "c" to the value. See imgResource.width for possible options.|
|width|string|width of the image. This can be a numeric value representing the fixed width of the image in pixels. But you can also perform simple calculations by adding "m" or "c" to the value. See imgResource.width for possible options.|
|minHeight|integer|minimum height of the image|
|minWidth|integer|minimum width of the image|
|maxHeight|integer|maximum height of the image|
|maxWidth|integer|maximum width of the image|

### Format/Slug

Converts the string into an URL slug. This includes replacing non-ASCII characters with their closest ASCII equivalents, removing remaining non-ASCII and non-alphanumeric characters, and replacing whitespace with `replacement`. Uses `Stringy`.

```php
{{ b::formatSlug(['str' => 'some test output']) }}

#short way, because you only need one argument
{{ b::formatSlug('some test output') }}
```

**Arguments**

|Name|Type|Description|
|---|---|
|str|string|The string you want to slugify|
|replacement|string|Default: '-', replaces whitespaces with this string.|

### Format/Tidy

Returns a string with smart quotes, ellipsis characters, and dashes from Windows-1252 (commonly used in Word documents) replaced by their ASCII equivalents. Uses `Stringy`.

```php
{{ b::formatTidy(['str' => '“I see…”']) }} // '"I see..."'

#short way, because there is only one argument
{{ b::formatTidy('“I see…”') }} // '"I see..."'
```

**Arguments**

|Name|Type|Description|
|---|---|
|str|string|The string you want to be tidy|

### loggedin

check if an user is logged in

```php
{{ b::loggedIn() }}

# use in condition
@if(b::loggedIn()) loggenIn @else loggedOut @endif
```

### userData

This Viewhelper renders data of the current user as string with given seperator.

```php
{{ b::userData(['fields' => ['uid','username'], 'seperator' => ', ']) }}
```

**Arguments**

|Name|Type|Description|
|---|---|
|fields|array|array of fields, comma seperated|
|seperator|string|the seperator between the fields, default is ' '|

### hasGroup

This Viewhelper checks if FE users has given group.

```php
<!-- just a boolean check-->
{{ b::hasGroup(['group' => 1] }}

<!-- use in condition -->
@if(b::hasGroup(['group' => 1]))
	user has Group 1
@else
	user doesn't have Group 1
@endif
```

**Arguments**

|Name|Type|Description|
|---|---|
|group|string|The usergroup (either the usergroup uid or its title).|
