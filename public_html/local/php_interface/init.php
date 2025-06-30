<?php

use Bitrix\Main\Loader;
use Bitrix\Iblock\IblockTable;
use Bitrix\Main\Diag\Debug;
use Bitrix\Main\Page\Asset;
use \Bitrix\Crm\Service\Container;


//\Bitrix\Main\UI\Extension::load('cab_log_events.common'); // вывод js событий


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
$eventManager->addEventHandler('iblock', 'OnIBlockPropertyBuildList', ['UserTypes\SelectDeal', 'GetUserTypeDescription']);

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

function OnAfterIBlockElementUpdateHandler(&$arFields)
{

    if (Loader::includeModule('iblock') && Loader::includeModule('crm')) {
        $arFieldsIblockID = $arFields['IBLOCK_ID'];
        $iblockCode = getIblockCodeHandler($arFieldsIblockID);
        $iblockCodeOpt = 'request';
        if ($iblockCode && $iblockCode == $iblockCodeOpt) {
            Debug::dumpToFile($arFields, 'OnAfterIBlockElementUpdateHandler', 'arFields.log');
            $dealFactory = Container::getInstance()->getFactory(CCrmOwnerType::Deal);
            $newDealItem = $dealFactory->getItem((int)$arFields["PROPERTY_VALUES"][75]["70:75"]["VALUE"]);
            $newDealItem->set('OPPORTUNITY', $arFields["PROPERTY_VALUES"][67]["70:67"]["VALUE"]);
            $newDealItem->set("ASSIGNED_BY_ID", $arFields["PROPERTY_VALUES"][68]["70:68"]["VALUE"]);
            $dealUpdateOperation = $dealFactory->getUpdateOperation($newDealItem);
            $addResult = $dealUpdateOperation->launch();

        }
    }
}

function OnAfterCrmDealUpdateHandler(&$arFields)
{
    Loader::includeModule('iblock');



    $dealId = $arFields['ID'];
    $dealOpportunityAccount = $arFields["OPPORTUNITY_ACCOUNT"];
    $dealAssignedByID = $arFields["ASSIGNED_BY_ID"];

    $arFilter = array(
        "IBLOCK_ID" => 18,
        "PROPERTY_DEAL" => $dealId
    );
    $res = \CIBlockElement::GetList(
        array("SORT" => "ASC"),
        $arFilter,
        false, false, ['IBLOCK_ID', 'ID']
    );
    while ($ob = $res->GetNextElement()) {
        $arElFields = $ob->GetFields();
    }

    $iElId = (int)$arElFields['ID'];
    Debug::dumpToFile($iElId, 'OnAfterIBlockElementUpdateHandler');
    if ($iElId) {
        $el = new \CIBlockElement;
        $PROP = array();
        $PROP[70] = $dealId; // Сделка в иб
        $PROP[67] = $dealOpportunityAccount;// Сумма
        $PROP[68] = $dealAssignedByID;// Ответственный

        $arLoadProductArray = array(
            //"MODIFIED_BY" => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_SECTION" => false,          // элемент лежит в корне раздела
            "PROPERTY_VALUES" => $PROP,
            "NAME" => "Элемент",
            "ACTIVE" => "Y",            // активен

        );
        $res = $el->Update($iElId, $arLoadProductArray);

    } else {
        echo "Элемент не найден.";
    }


}