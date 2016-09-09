<?php
namespace AuM\Blypo\ViewHelper;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
Class Pagination extends \AuM\Blypo\ViewHelper\ViewHelper{
	
	public $linkParams;
	public $pageUid;
	public static $cObj = false;
	
	public function render(){
		$total = $this->arguments['total'];
		$itemsPerPage = isset($this->arguments['perPage']) ? $this->arguments['perPage'] : 10;
		$page = isset($_GET['page']) ? (int) $_GET['page'] : 0;
		$range = isset($this->arguments['range']) ? $this->arguments['range']: 4;
		$this->linkParams = isset($this->arguments['params']) ? $this->arguments['params'] : false;
		$wrapper = isset($this->arguments['wrapper']) ? $this->arguments['wrapper'] : 'ul';
		$itemTag = isset($this->arguments['itemTag']) ? $this->arguments['itemTag'] : 'li';
		$this->pageUid = isset($this->arguements['pageUid']) ?  $this->arguements['pageUid'] : $GLOBALS['TSFE']->id;
		echo $this->makePagination($total, $itemsPerPage, $page, $range, $wrapper, $itemTag);
	}
	
	public function makePagination($total, $itemsPerPage, $page, $range, $wrapper, $itemTag){
		$pages = ceil($total/$itemsPerPage);
		if($pages == 0 || $pages == 1){
			return '';
		}
		$output = [];
		// Prev/First Links
		if($page > 0){
			$output[] = [
				'index' => 0,
				'label' => '«',
				'active' => '',
				'class' => 'first-link',
				'link' => $this->makeLink(0)
			];
			$output[] = [
				'index' => $page -1,
				'label' => '‹',
				'active' => '',
				'class' => 'prev-link',
				'link' => $this->makeLink($page -1)
			];
		}

		if($pages > $range){
			// start
			if($page < $range){
				for($i = 0; $i <= ($range); $i++){
					$output[] = [
						'index' => $i,
						'label' => $i + 1,
						'active' => (int)$i === $page?'active':'',
						'class' => '',
						'link' => $this->makeLink($i)
					];
				}
			}
			// end
			elseif($page >= ($pages - ceil(($range/2)))){
			 	for($i = $pages - $range; $i < $pages; $i++){
					$output[] = [
						'index' => $i,
						'label' => $i + 1,
						'active' => (int)$i === $page?'active':'',
						'class' => '',
						'link' => $this->makeLink($i)
					];
				}
			}
			// middle
			elseif($page >= $range && $paged < ($pages - ceil(($range/2)))){
		        for($i = ($page - ceil($range/2)); $i <= ($page + ceil(($range/2))); $i++){
					$output[] = [
						'index' => $i,
						'label' => $i + 1,
						'active' => (int)$i === $page?'active':'',
						'class' => '',
						'link' => $this->makeLink($i)
					];
		        }
		      }
		}else {
			for($i = 0; $i < $pages; $i++){
				$output[] = [
					'index' => $i,
					'label' => $i + 1,
					'active' => $i === $page?'active':'',
					'class' => '',
					'link' => $this->makeLink($i)
				];
			}
		}
		
		// Next/Last Links
		if($page < $pages -1){
			$output[] = [
				'index' => $page +1,
				'label' => '›',
				'active' => '',
				'class' => 'next-link',
				'link' => $this->makeLink($page +1)
			];
			$output[] = [
				'index' => $pages -1,
				'label' => '»',
				'active' => '',
				'class' => 'last-link',
				'link' => $this->makeLink($pages -1)
			];
		}
		$content = '<'.$wrapper.'>';
		foreach($output as $link){
			$content.= '<'.$itemTag.' class="'.$link['class'].' '.$link['active'].'">';
			$content.= '<a href="'.$link['link'].'">'.$link['label'].'</a>';
			$content.= '</'.$itemTag.'>';
		}
		$content.= '</'.$wrapper.'>';
		return $content;
	}

	public function makeLink($page){
		if(!static::$cObj){
			static::$cObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\ContentObject\\ContentObjectRenderer');
        }
		if($this->linkParams) {
			$linkparams = '&'.http_build_query($this->linkParams,'&');
		} else {
			$linkparams = '';
		}
		$url = static::$cObj->typoLink_URL([
			'parameter' => $this->pageUid, 
			'additionalParams' => '&page='.$page.$linkparams
		]);
		return $url;
	}
}