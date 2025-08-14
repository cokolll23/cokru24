<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

use Bitrix\Crm\DealTable;
use Bitrix\Main\Loader;
use Bitrix\Main\Entity;

Loader::includeModule('crm');
Loader::includeModule('lab.crmcustomtab');
// todo перенести компонент в модуль
// todo для того чтобы слайдер б24 грузил все стили и BX библиотеку оборачиваем в bitrix:ui.sidepanel.wrapper


$APPLICATION->IncludeComponent(
    'bitrix:ui.sidepanel.wrapper',
    '',
    [
        'POPUP_COMPONENT_NAME' => 'lab.crmcustomtab:deals.grid',
        'POPUP_COMPONENT_TEMPLATE_NAME' => '',
        'POPUP_COMPONENT_PARAMS' => [
            'id' => $_REQUEST['id']
        ]
    ]
);



require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");