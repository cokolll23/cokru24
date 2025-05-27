<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Компонент списка таблицы базы данных");


$APPLICATION->IncludeComponent(
    "otus:table.currencies",
    "list",
    array(
        "COMPONENT_TEMPLATE" => "list",
        "CURRENCIES_LIST_TITLE" => "USD",
    ),
    false
);



require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");