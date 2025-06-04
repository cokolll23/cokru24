<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if (!CModule::includeModule('currency')) {
    return;
}

$iterator = Bitrix\Currency\CurrencyTable::getList([
    'select' => ['CURRENCY','NUMCODE'],
]);

$arCurrencyTitles = [];

foreach ($iterator as $currency) {
    $arCurrencyTitles[$currency['NUMCODE']] = $currency['CURRENCY'];
}

$arComponentParameters = array(
	"GROUPS" => array(
	),
	"PARAMETERS" => array(

        "CURRENCIES" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("CURR_SELECT"),
            "TYPE" => "LIST",
            "DEFAULT" => 643,
            "VALUES" => $arCurrencyTitles
        ),

		"CACHE_TIME"  =>  Array("DEFAULT"=>3600),

	),
);


?>