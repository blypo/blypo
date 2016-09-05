<?php
namespace AuM\Blypo\Service;

Class ExceptionService{
	public static function throwException($msg, $title = 'Error', $type = 'info'){
		$view = new \AuM\Blypo\View\BladeView();
		$view->assignMultiple(['msg' => $msg, 'title' => $title, 'type' => $type]);
		$view->setViewPath('EXT:blypo/Resources/Views');
		$view->setFileName('Exception.alert');
		$view->initializeView();
		echo $view->render();
	}
}