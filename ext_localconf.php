<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

require_once TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('blypo').'/vendor/autoload.php';

/* Declarating the Blade Template cObj */
$TYPO3_CONF_VARS['SC_OPTIONS']['tslib/class.tslib_content.php']['cObjTypeAndClass'][AuM\Blypo\ObjectRender\BladePageObjectRender::CONTENT_OBJECT_NAME] = array(
	AuM\Blypo\ObjectRender\BladePageObjectRender::CONTENT_OBJECT_NAME,
	'AuM\Blypo\ObjectRender\BladePageObjectRender'
);

// Clear Cache Hook
$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc']['blypo_views'] = 'AuM\Blypo\Hooks\ClearCache->clear';
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['blypo']['defaultViewHelpers']['b'] = '\AuM\Blypo\ViewHelper';

//if this function returns true, the templates will not be cached and freshly compiled, replace it with whatever suits you
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['blypo']['sideStepCaching'] = function(){
	return isset($_GET['no_cache']) && (int)$_GET['no_cache'] === 1;
};