<?php

namespace AuM\Blypo\ViewHelper;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

Class FormatHtml extends \AuM\Blypo\ViewHelper\ViewHelper{
	public function render($value = false){
		if(!$value) {
			$value = isset($this->arguments['value']) ? $this->arguments['value']: '';
		}
        $parseFunc = isset($this->arguments['parseFunc']) ? $this->arguments['parseFunc']: 'lib.parseFunc_RTE';
        
		if (TYPO3_MODE === 'BE') {
            self::simulateFrontendEnvironment();
        }
        $contentObject = GeneralUtility::makeInstance(ContentObjectRenderer::class);
        $content = $contentObject->parseFunc($value, array(), '< ' . $parseFunc);
        if (TYPO3_MODE === 'BE') {
            self::resetFrontendEnvironment();
        }
        return $content;
		
    }

    /**
     * Copies the specified parseFunc configuration to $GLOBALS['TSFE']->tmpl->setup in Backend mode
     * This somewhat hacky work around is currently needed because the parseFunc() function of \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer relies on those variables to be set
     *
     * @return void
     */
    protected static function simulateFrontendEnvironment()
    {
        self::$tsfeBackup = isset($GLOBALS['TSFE']) ? $GLOBALS['TSFE'] : null;
        $GLOBALS['TSFE'] = new \stdClass();
        $GLOBALS['TSFE']->tmpl = new \stdClass();
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $configurationManager = $objectManager->get(ConfigurationManagerInterface::class);
        $GLOBALS['TSFE']->tmpl->setup = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
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