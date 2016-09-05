<?php
namespace AuM\Blypo\ViewHelper;
use TYPO3\CMS\Core\Utility\GeneralUtility;
class CObject extends \AuM\Blypo\ViewHelper\ViewHelper {

    /**
     * Disable the escaping interceptor because otherwise the child nodes would be escaped before this view helper
     * can decode the text's entities.
     *
     * @var bool
     */
    protected $escapingInterceptorEnabled = false;

    /**
     * @var array
     */
    protected $typoScriptSetup;

    /**
     * @var \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController contains a backup of the current $GLOBALS['TSFE'] if used in BE mode
     */
    protected $tsfeBackup;

    /**
     * Renders the TypoScript object in the given TypoScript setup path.
     *
     * @param string $typoscriptObjectPath the TypoScript setup path of the TypoScript object to render
     * @param mixed $data the data to be used for rendering the cObject. Can be an object, array or string. If this argument is not set, child nodes will be used
     * @param string $currentValueKey
     * @param string $table
     * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception
     * @return string the content of the rendered TypoScript object
     */
    public function render()
    {
    	$this->configurationManager = GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Configuration\ConfigurationManager');
        $this->typoScriptSetup = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
		
    	$data = isset($this->arguments['data']) ? $this->arguments['data'] : array();
    	$currentValueKey = isset($this->arguments['currentValueKey']) ? $this->arguments['currentValueKey'] : null;
    	$table = isset($this->arguments['table']) ? $this->arguments['table'] : '';
		$typoscriptObjectPath = $this->arguments['tspath'];
		
        if (TYPO3_MODE === 'BE') {
            $this->simulateFrontendEnvironment();
        }
        $currentValue = null;
        if (is_object($data)) {
            $data = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getGettableProperties($data);
        } elseif (is_string($data) || is_numeric($data)) {
            $currentValue = (string)$data;
            $data = array($data);
        }
        /** @var \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $contentObject */
        $contentObject = GeneralUtility::makeInstance(\TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer::class);
        $contentObject->start($data, $table);
        if ($currentValue !== null) {
            $contentObject->setCurrentVal($currentValue);
        } elseif ($currentValueKey !== null && isset($data[$currentValueKey])) {
            $contentObject->setCurrentVal($data[$currentValueKey]);
        }
        $pathSegments = GeneralUtility::trimExplode('.', $typoscriptObjectPath);
        $lastSegment = array_pop($pathSegments);
        $setup = $this->typoScriptSetup;
        foreach ($pathSegments as $segment) {
            if (!array_key_exists(($segment . '.'), $setup)) {
                //throw new \TYPO3\CMS\Fluid\Core\ViewHelper\Exception('TypoScript object path "' . htmlspecialchars($typoscriptObjectPath) . '" does not exist', 1253191023);
            	// TODO #exception
			}
            $setup = $setup[$segment . '.'];
        }
        $content = $contentObject->cObjGetSingle($setup[$lastSegment], $setup[$lastSegment . '.']);
        if (TYPO3_MODE === 'BE') {
            $this->resetFrontendEnvironment();
        }
        return $content;
    }

    /**
     * Sets the $TSFE->cObjectDepthCounter in Backend mode
     * This somewhat hacky work around is currently needed because the cObjGetSingle() function of \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer relies on this setting
     *
     * @return void
     */
    protected function simulateFrontendEnvironment()
    {
        $this->tsfeBackup = isset($GLOBALS['TSFE']) ? $GLOBALS['TSFE'] : null;
        $GLOBALS['TSFE'] = new \stdClass();
        $GLOBALS['TSFE']->cObjectDepthCounter = 100;
    }

    /**
     * Resets $GLOBALS['TSFE'] if it was previously changed by simulateFrontendEnvironment()
     *
     * @return void
     * @see simulateFrontendEnvironment()
     */
    protected function resetFrontendEnvironment()
    {
        $GLOBALS['TSFE'] = $this->tsfeBackup;
    }
}
