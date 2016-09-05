<?php

namespace AuM\Blypo\ViewHelper;

use TYPO3\CMS\Core\Charset\CharsetConverter;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class FormatCrop extends \AuM\Blypo\ViewHelper\ViewHelper {

	protected static $tsfeBackup;

	public function render(){
		$stringToTruncate = isset($this->arguments['str']) ? $this->arguments['str'] : '';
		$maxCharacters = isset($this->arguments['maxCharacters']) ? $this->arguments['maxCharacters'] : 100;
		$append = isset($this->arguments['append']) ? $this->arguments['append'] : '…';
		$respectWordBoundaries = isset($this->arguments['respectWordBoundaries']) ? $this->arguments['respectWordBoundaries'] : true; 
		$respectHtml = isset($this->arguments['respectHtml']) ? $this->arguments['respectHtml'] : true;
		

        if (TYPO3_MODE === 'BE') {
            self::simulateFrontendEnvironment();
        }

        // Even if we are in extbase/fluid context here, we're switching to a casual class of the framework here
        // that has no dependency injection and other stuff. Therefor it is ok to use makeInstance instead of
        // the ObjectManager here directly for additional performance
        // Additionally, it would be possible to retrieve the "current" content object via ConfigurationManager->getContentObject(),
        // but both crop() and cropHTML() are "nearly" static and do not depend on current content object settings, so
        // it is safe to use a fresh instance here directly.
        /** @var ContentObjectRenderer $contentObject */
        $contentObject = GeneralUtility::makeInstance(ContentObjectRenderer::class);
        if ($respectHtml) {
            $content = $contentObject->cropHTML($stringToTruncate, $maxCharacters . '|' . $append . '|' . $respectWordBoundaries);
        } else {
            $content = $contentObject->crop($stringToTruncate, $maxCharacters . '|' . $append . '|' . $respectWordBoundaries);
        }
        if (TYPO3_MODE === 'BE') {
            self::resetFrontendEnvironment();
        }
        echo $content;
	}

    /**
     * Sets the global variables $GLOBALS['TSFE']->csConvObj and $GLOBALS['TSFE']->renderCharset in Backend mode
     * This somewhat hacky work around is currently needed because the crop() and cropHTML() functions of
     * ContentObjectRenderer rely on those variables to be set
     *
     * @return void
     */
    protected static function simulateFrontendEnvironment()
    {
        self::$tsfeBackup = isset($GLOBALS['TSFE']) ? $GLOBALS['TSFE'] : null;
        $GLOBALS['TSFE'] = new \stdClass();
        // preparing csConvObj
        if (!is_object($GLOBALS['TSFE']->csConvObj)) {
            if (is_object($GLOBALS['LANG'])) {
                $GLOBALS['TSFE']->csConvObj = $GLOBALS['LANG']->csConvObj;
            } else {
                $GLOBALS['TSFE']->csConvObj = GeneralUtility::makeInstance(CharsetConverter::class);
            }
        }
        // preparing renderCharset
        if (!is_object($GLOBALS['TSFE']->renderCharset)) {
            if (is_object($GLOBALS['LANG'])) {
                $GLOBALS['TSFE']->renderCharset = $GLOBALS['LANG']->charSet;
            } else {
                $GLOBALS['TSFE']->renderCharset = 'utf-8';
            }
        }
    }

    /**
     * Resets $GLOBALS['TSFE'] if it was previously changed by simulateFrontendEnvironment()
     *
     * @return void
     * @see simulateFrontendEnvironment()
     */
    protected static function resetFrontendEnvironment()
    {
        $GLOBALS['TSFE'] = self::$tsfeBackup;
    }
}
