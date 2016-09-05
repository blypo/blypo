<?php

namespace AuM\Blypo\Directive;

// just hint the use of static method at runtime
abstract class StaticDirective{
	// also make sure that an error is thrown somewhere if
	// the subclass does not provide public+static implementation of render method
	abstract public static function render($expression);
}
