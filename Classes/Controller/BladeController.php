<?php

namespace AuM\Blypo;
use TYPO3\CMS\Core\Utility\GeneralUtility;
class BladeController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	* The default view object to use if none of the resolved views can render
	* a response for the current request.
	*
	* @var string
	* @api
	*/
	protected $defaultViewObjectName = \AuM\Blypo\View\BladeView::class;
	public $viewPath = 'EXT:@extension/Resources/Private/Views';

	protected function resolveView()
	{
		$view = GeneralUtility::makeInstance(\AuM\Blypo\View\BladeView::class);
		$view->setControllerContext($this->controllerContext);
		$actionRequest = $this->controllerContext->getRequest();
		$view->setViewPath(str_replace('@extension',$actionRequest->getControllerExtensionKey(),$this->viewPath));
		$view->setFileName($actionRequest->getControllerActionName());
		$view->setFilePath($actionRequest->getControllerName());
		$view->assign('settings', $this->settings);
		$view->initializeView();
		return $view;
    }

}
