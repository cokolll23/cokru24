<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Компонент списка таблицы базы данных");

$APPLICATION->IncludeComponent(
    "otus:table.views",
    "list",
    array(
        "COMPONENT_TEMPLATE" => "list",
        "SHOW_CHECKBOXES" => "Y",
        "NUM_PAGE" => "1"
    ),
    false
);



require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");