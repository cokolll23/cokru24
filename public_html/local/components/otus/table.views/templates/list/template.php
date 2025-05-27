<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);


$iterator = Bitrix\Currency\CurrencyTable::getList([
    'select' => ['CURRENCY', 'AMOUNT_CNT','AMOUNT',],
    'filter' => [],
    'order' => []
])->fetchAll();

dump($iterator);

$nav = new \Bitrix\Main\UI\PageNavigation('report_list');
$nav->setRecordCount($arResult['COUNT']);
$nav->allowAllRecords(false)->setPageSize($arResult['NUM_PAGE'])->initFromUri();

$APPLICATION->includeComponent(
    "bitrix:main.ui.grid",
    "",
    [
        "GRID_ID" => "MY_GRID_ID",
        "COLUMNS" => $arResult['COLUMNS'],
        "ROWS" => $arResult['LISTS'],
        "NAV_OBJECT" => $nav,
        "AJAX_MODE" => "Y",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_HISTORY" => "N",
        "SHOW_ROW_CHECKBOXES" => $arResult['SHOW_ROW_CHECKBOXES'],
        "SHOW_SELECTED_COUNTER" => false,
        "SHOW_PAGESIZE" => false,
        "TOTAL_ROWS_COUNT" => $arResult['COUNT']
    ]
);

$APPLICATION->IncludeComponent("bitrix:currency.rates","",Array(
        "arrCURRENCY_FROM" => Array("RUB", "USD", "EUR"),
        "CURRENCY_BASE" => "RUB",
        "RATE_DAY" => "",
        "SHOW_CB" => "Y",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "86400"
    )
);
