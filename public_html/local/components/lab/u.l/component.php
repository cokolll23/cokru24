<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (!CModule::IncludeModule("currency")) {
    $this->AbortResultCache();
    ShowError("IBLOCK_MODULE_NOT_INSTALLED");
    return false;
}
$data = Bitrix\Currency\CurrencyTable::getList([
    'select' => ['CURRENCY', 'AMOUNT_CNT','AMOUNT','CURRENT_BASE_RATE','NUMCODE',],
    'filter' => ['NUMCODE' => $arParams["CURRENCIES"]],
])->fetchAll();
$arResult = $data;
$this->IncludeComponentTemplate();
?>