<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arCurrentValues */

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
        "LIST" => array(
            "NAME" => GetMessage("GRID_PARAMETERS"),
            "SORT" => "300"
        )
    ),
    "PARAMETERS" => array(
        "CURRENCIES" => array(
            "PARENT" => "LIST",
            "NAME" => GetMessage("CURR_SELECT"),
            "TYPE" => "LIST",
            "DEFAULT" => "N",
            "VALUES" => $arCurrencyTitles
        ),

    )
);


