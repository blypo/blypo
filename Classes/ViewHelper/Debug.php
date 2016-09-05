<?php
namespace AuM\Blypo\ViewHelper;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class Debug extends \AuM\Blypo\ViewHelper\ViewHelper {
    public function render(){
    	$arguments['maxDepth'] = isset($this->arguments['maxDepth']) ? $this->arguments['maxDepth']: 10;
        return DebuggerUtility::var_dump($this->arguments, $arguments['title'], $arguments['maxDepth'], (bool)$arguments['plainText'], (bool)$arguments['ansiColors'], (bool)$arguments['inline'], $arguments['blacklistedClassNames'], $arguments['blacklistedPropertyNames']);
    }

}
