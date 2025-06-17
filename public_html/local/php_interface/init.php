<?php

use Bitrix\Main\Loader;
use Bitrix\Iblock\IblockTable;
use Bitrix\Main\Diag\Debug;
use Bitrix\Main\Page\Asset;



//\Bitrix\Main\UI\Extension::load('cab_custom.common','jquery');


if (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
    require_once __DIR__ . '/../../vendor/autoload.php';
}
if (file_exists(__DIR__ . '/../app/autoloader.php')) {
    require_once __DIR__ . '/../app/autoloader.php';
}
if (file_exists(__DIR__ . '/../include/functions/pretty_print.php')) {
    require_once __DIR__ . '/../include/functions/pretty_print.php';
}

$eventManager = \Bitrix\Main\EventManager::getInstance();

// после изменения записи в сделке
/*$eventManager->addEventHandlerCompatible("crm", "OnAfterCrmDealUpdate",'OnAfterCrmDealUpdateHandler');
$eventManager->addEventHandler("iblock", "OnAfterIBlockElementUpdate",'OnAfterIBlockElementUpdateHandler');
$eventManager->addEventHandlerCompatible("crm", "OnAfterCrmDealAdd",'OnAfterCrmDealAddHandler');
*/

// для создания кастомных свойств
$eventManager->addEventHandler('iblock', 'OnIBlockPropertyBuildList', ['UserTypes\SignUpForProcedure', 'GetUserTypeDescription']);
//$eventManager->addEventHandler('iblock', 'OnIBlockPropertyBuildList', ['EventsClasses\ProceduresDateTimeBron', 'GetUserTypeDescription']);

$eventManager->addEventHandler("iblock", "OnAfterIBlockElementAdd", 'OnAfterIBlockElementAddHandler');
$eventManager->addEventHandler("iblock", "OnAfterIBlockElementUpdate", 'OnAfterIBlockElementUpdateHandler');
$eventManager->addEventHandlerCompatible("crm", "OnAfterCrmDealUpdate", 'OnAfterCrmDealUpdateHandler');


function getIblockCodeHandler($arFieldsIblockID)
{
    $result = IblockTable::getList(array(
        'filter' => ['ID' => $arFieldsIblockID],
        'select' => ['CODE']
    ));
    if ($iblock = $result->fetch()) {
        $iblockCode = $iblock['CODE'];
    }
    return $iblockCode;
}


function OnAfterIBlockElementAddHandler(&$arFields)
{
    // dump($arFields);
    // die();
    if (Loader::includeModule('iblock') && Loader::includeModule('crm')) {
        $arFieldsIblockID = $arFields['IBLOCK_ID'];
        $iblockCode = getIblockCodeHandler($arFieldsIblockID);
        $iblockCodeOpt = 'request';
        if ($iblockCode && $iblockCode == $iblockCodeOpt) {
            $dealFactory = \Bitrix\Crm\Service\Container::getInstance()->getFactory(CCrmOwnerType::Deal);
            $newDealItem = $dealFactory->createItem();
            $newDealItem->set('TITLE', $arFields['NAME']);
            $newDealItem->set('OPPORTUNITY', $arFields["PROPERTY_VALUES"][67]['n0']["VALUE"]);
            $dealAddOperation = $dealFactory->getAddOperation($newDealItem);
            $addResult = $dealAddOperation->launch();

        }


    } else {
        echo "Модуль инфоблоков не подключен.";

    }

    if ($iblockCode == $iblockCodeOpt) {

        /*


        NAME=>ЭКГ
                "PROPERTY_VALUES" => array:2 [▼
            68 => array:1 [▼
              "n0" => array:1 [▼
                "VALUE" => "Иванов"
              ]
            ]
            67 => array:1 [▼
              "n0" => array:1 [▼
                "VALUE" => "12000" OPPORTUNITY
              ]
            ]
          ]*/

    }
}

function OnAfterIBlockElementUpdateHandler(&$arFields)
{

}