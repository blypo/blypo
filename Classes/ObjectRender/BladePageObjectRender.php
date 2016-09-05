<?php

namespace AuM\Blypo\ObjectRender;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Service\TypoScriptService;
use Philo\Blade\Blade;

class BladePageObjectRender {

	const CONTENT_OBJECT_NAME = 'BLYPO_TEMPLATE';
	private $cObj = null;
	public $cachePath = 'typo3temp/Blypo';
	public $view;

	public function cObjGetSingleExt( $name, $conf, $TSkey, $parent ) {
		
        $this->contentDataProcessor = GeneralUtility::makeInstance(\TYPO3\CMS\Frontend\ContentObject\ContentDataProcessor::class);
		
		// if no file -> error
		if(!$conf['file']){
			return 'Keine Datei angegeben';
		}
		
		// if no view path -> error
		if(!$conf['paths.']['views']){
			return 'Keinen Pfad für Views angegeben';
		}
		
		$this->cObj = $parent;
		
		// now call view
		$this->view = GeneralUtility::makeInstance(\AuM\Blypo\View\BladeView::class);
		$this->view->setViewPath($conf['paths.']['views']);
		$this->view->setFileName($conf['file']);
		
		// set other cache path if given
		if($conf['paths.']['cache']) {
			$this->cachePath = $conf['paths.']['cache'];
		}
		// assign settings
		$this->assignSettings($conf);
		
		// get variables out of conf
		$variables = $this->getContentObjectVariables($conf);
        $variables = $this->contentDataProcessor->process($this->cObj, $conf, $variables);
		$this->view->assignMultiple($variables);
		
		$blade = new Blade(GeneralUtility::getFileAbsFileName($conf['paths.']['views']),GeneralUtility::getFileAbsFileName($this->cachePath));
		
		$this->view->blade = $blade;
		$this->view->setDirectives();
		
		return $this->view->render();
	}
	
	public function assignSettings(array $conf){
        if (isset($conf['settings.'])) {
            /** @var $typoScriptService TypoScriptService */
            $typoScriptService = GeneralUtility::makeInstance(TypoScriptService::class);
            $settings = $typoScriptService->convertTypoScriptArrayToPlainArray($conf['settings.']);
            $this->view->assign('settings', $settings);
        }
    }
	
	/**
     * Compile rendered content objects in variables array ready to assign to the view
     *
     * @param array $conf Configuration array
     * @return array the variables to be assigned
     * @throws \InvalidArgumentException
     */
    public function getContentObjectVariables(array $conf)
    {
        $variables = array();
        $reservedVariables = array('data', 'current');
        // Accumulate the variables to be process and loop them through cObjGetSingle
        $variablesToProcess = (array)$conf['variables.'];
        foreach ($variablesToProcess as $variableName => $cObjType) {
            if (is_array($cObjType)) {
                continue;
            }
            if (!in_array($variableName, $reservedVariables)) {
                $variables[$variableName] = $this->cObj->cObjGetSingle($cObjType, $variablesToProcess[$variableName . '.']);
            } else {
                throw new \InvalidArgumentException(
                    'Cannot use reserved name "' . $variableName . '" as variable name in FLUIDTEMPLATE.',
                    1288095720
                );
            }
        }
        $variables['data'] = $this->cObj->data;
        $variables['current'] = $this->cObj->data[$this->cObj->currentValKey];
        return $variables;
    }
}