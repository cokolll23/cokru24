<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arCurrentValues */

if(!CModule::IncludeModule("iblock"))
    return;

$iterator = Bitrix\Currency\CurrencyTable::getList([
    'select' => ['CURRENCY'],
]);

/*$arCurrencyTitles = [];

foreach ($iterator as $currency) {
    $arCurrencyTitles[]=$currency['CURRENCY'];
}*/
$arComponentParameters = array(
    "GROUPS" => array(
        "LIST"=>array(
            "NAME"=>GetMessage("GRID_PARAMETERS"),
            "SORT"=>"300"
        )
    ),
    "PARAMETERS" => array(
        "CURRENCIES_LIST_TITLE" =>  array(
            "PARENT" => "LIST",
            "NAME"=>GetMessage("SHOW_ACTION_BTNS"),
            "TYPE"=>"LIST",
            "DEFAULT"=>"N",
            "VALUES"=>["EUR","USD"]
        ),

    )
);


