<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Компонент списка таблицы базы данных");

/*$iterator = Bitrix\Currency\CurrencyTable::getList([
    'select' => ['CURRENCY'],
]);

$arCurrencyTitles = [];

foreach ($iterator as $currency) {
    $arCurrencyTitles[$currency['CURRENCY']]=$currency['CURRENCY'];
}*/
//dump($arCurrencyTitles);

/*$APPLICATION->IncludeComponent(
    "otus:table.currencies",
    "list",
    array(
        "COMPONENT_TEMPLATE" => "list"
    ),
    false
);*/
$APPLICATION->IncludeComponent(
	"lab:u.l", 
	".default", 
	array(
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"IBLOCK_ID" => "19",
		"IBLOCK_TYPE" => "news",
		"ITEMS_LIMIT" => "10",
		"COMPONENT_TEMPLATE" => ".default",
		"CURRENCIES" => "840"
	),
	false
);



require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");