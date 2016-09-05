<?php

namespace AuM\Blypo\ViewHelper;

Class FormatCurrency extends \AuM\Blypo\ViewHelper\ViewHelper{
public function render(){
		$currencySign = isset($this->arguments['currencySign']) ? $this->arguments['currencySign']: '';
        $decimalSeparator = isset($this->arguments['decimalSeparator']) ? $this->arguments['decimalSeparator']: ',';
        $thousandsSeparator = isset($this->arguments['thousandsSeparator']) ? $this->arguments['thousandsSeparator']: '.';
        $prependCurrency = isset($this->arguments['prependCurrency']) ? $this->arguments['prependCurrency']: false;
        $separateCurrency = isset($this->arguments['separateCurrency']) ? $this->arguments['separateCurrency']: true;
        $decimals = isset($this->arguments['decimals']) ? $this->arguments['decimals']: 2;
        $floatToFormat = $this->arguments['value'];
        if (empty($floatToFormat)) {
            $floatToFormat = 0.0;
        } else {
            $floatToFormat = floatval($floatToFormat);
        }
        $output = number_format($floatToFormat, $decimals, $decimalSeparator, $thousandsSeparator);
        if ($currencySign !== '') {
            $currencySeparator = $separateCurrency ? ' ' : '';
            if ($prependCurrency === true) {
                $output = $currencySign . $currencySeparator . $output;
            } else {
                $output = $output . $currencySeparator . $currencySign;
            }
        }
        return $output;
    }
}