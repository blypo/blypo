<?php

namespace AuM\Blypo\ViewHelper;

use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class FormatBytes extends \AuM\Blypo\ViewHelper\ViewHelper {

	public static $units = array();
    public static $defaultDecSep = ',';
    public static $defaultThouSep = '.';

	public function render(){
		$decimals = isset($this->arguments['decimals']) ? $this->arguments['decimals'] : 2;
		$decSep = isset($this->arguments['decSep']) ? $this->arguments['decSep'] : false;
		$thouSep = isset($this->arguments['thouSep']) ? $this->arguments['thouSep'] : false;
        $value = $this->arguments['value'];
        if ($value === null) {
            //TODO #exception
        }
        if (empty(self::$units)) {
            self::$units = GeneralUtility::trimExplode(',', LocalizationUtility::translate('viewhelper.format.bytes.units', 'fluid'));
        }
        if (!is_integer($value) && !is_float($value)) {
            if (is_numeric($value)) {
                $value = (float)$value;
            } else {
                $value = 0;
            }
        }
        $bytes = max($value, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count(self::$units) - 1);
        $bytes /= pow(2, (10 * $pow));

        echo sprintf(
            '%s %s',
            number_format(round($bytes, 4 * $this->arguments['decimals']), $this->arguments['decimals'], $this->arguments['decimalSeparator'], $this->arguments['thousandsSeparator']),
            self::$units[$pow]
        );

	}

}