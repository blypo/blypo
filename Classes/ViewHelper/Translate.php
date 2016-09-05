<?php
namespace AuM\Blypo\ViewHelper;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
Class Translate extends \AuM\Blypo\ViewHelper\ViewHelper{
	
	public function render(){
		$key = $this->arguments['key'];
        $id = $this->arguments['id'];
        $default = $arguments['default'];
        $htmlEscape = $arguments['htmlEscape'];
        $extensionName = $this->arguments['extensionName'];
        $arguments = $arguments['arguments'];
        // Wrapper including a compatibility layer for TYPO3 Flow Translation
        if ($id === null) {
            $id = $key;
        }

        if ((string)$id === '') {
            \AuM\Blypo\Service\ExceptionService::throwException('An argument "key" or "id" has to be provided');
        }
        $request = static::$renderingContext->getControllerContext()->getRequest();
        $extensionName = $extensionName === null ? $request->getControllerExtensionName() : $extensionName;
        $value = LocalizationUtility::translate($id, $extensionName, $arguments);
        if ($value === null) {
            $value = $default !== null ? $default : '';
            if (!empty($arguments)) {
                $value = vsprintf($value, $arguments);
            }
        } elseif ($htmlEscape) {
            $value = htmlspecialchars($value);
        }
        return $value;
	}

}

