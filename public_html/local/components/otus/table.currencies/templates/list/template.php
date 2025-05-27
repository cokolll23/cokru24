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
    'select' => ['CURRENCY'],
    'filter' => [],
    'order' => []
])->fetchAll();
$arCurrencyTitles = [];

foreach ($iterator as $currency) {
    $arCurrencyTitles[]=$currency['CURRENCY'];
}


//dump($arParams);





