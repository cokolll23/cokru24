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

$eventManager->addEventHandler("iblock", "OnAfterIBlockElementUpdate",['\EventsHandlers\OnAfterIBlockElementUpdateHandler', 'OnAfterIBlockElementUpdateHandler']);
$eventManager->addEventHandlerCompatible("crm", "OnBeforeCrmDealUpdate", ['\EventsHandlers\OnBeforeCrmDealUpdateHandler','OnBeforeCrmDealUpdateHandler']);


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
/*function OnAfterIBlockElementUpdateHandler(&$arFields)
{
    if ( Loader::includeModule('crm')) {
        $arFieldsIblockID = $arFields['IBLOCK_ID'];
        $iblockCode = getIblockCodeHandler($arFieldsIblockID);
        $iblockCodeOpt = 'request';

        if ($iblockCode && $iblockCode == $iblockCodeOpt) {

            $dealId = (int)$arFields["PROPERTY_VALUES"][75]["70:75"]["VALUE"];

            $strDealSumma =$arFields["PROPERTY_VALUES"][67]["70:67"]["VALUE"];


            $dealFactory = Container::getInstance()->getFactory(CCrmOwnerType::Deal);
            $newDealItem = $dealFactory->getItem($dealId);

            Debug::dumpToFile($arFields, '$arFields ' . date('d-m-Y; H:i:s'));
            //Debug::dumpToFile($strDealSumma, '$strDealSumma ' . date('d-m-Y; H:i:s'));


            if (is_array($arFields["PROPERTY_VALUES"][67]["70:67"])) {
                $newDealItem->set('OPPORTUNITY', (int)$arFields["PROPERTY_VALUES"][67]["70:67"]["VALUE"]);
            }
            $newDealItem->set("ASSIGNED_BY_ID", $arFields["PROPERTY_VALUES"][68]["70:68"]["VALUE"]);
            $dealUpdateOperation = $dealFactory->getUpdateOperation($newDealItem);
            $addResult = $dealUpdateOperation->launch();
        }
    }
}*/

/*function OnBeforeCrmDealUpdateHandler(&$arFields)
{
    // get измененные значения
    $dealId = $arFields['ID'];

    if ($arFields["OPPORTUNITY"] && $arFields["OPPORTUNITY"] != '') {
        $strDealSumma = $arFields["OPPORTUNITY"];
    } else {
        $factory = Container::getInstance()->getFactory(\CCrmOwnerType::Deal);
        $getCurrDealRes['dealId'] = $item = $factory->getItem((int)$dealId);

        $strDealSumma = $item->get("OPPORTUNITY");


    }
    if ($arFields["ASSIGNED_BY_ID"] && $arFields["ASSIGNED_BY_ID"] != '') {
        $strDealOtvetctvenniy = $arFields["ASSIGNED_BY_ID"];
    }

    $arFilter = array(
        "IBLOCK_ID" => 18,
        "PROPERTY_DEAL" => $dealId
    );
// получить id элемента заказа по свойству Сделка 75 DEAL

    $res = \CIBlockElement::GetList(
        array("SORT" => "ASC"),
        $arFilter,
        false, false, ['IBLOCK_ID', 'ID']
    );

    while ($ob = $res->GetNextElement()) {
        $arElFields = $ob->GetFields();
    }

    $iElId = (int)$arElFields['ID'];



    $sqlQuery = " UPDATE b_iblock_element_prop_s18 SET PROPERTY_75 = '" . $dealId . "', PROPERTY_67 =  '" . $strDealSumma . "', PROPERTY_68 = '" . $strDealOtvetctvenniy . "' WHERE IBLOCK_ELEMENT_ID = '" . $iElId . "'";

    $connection = \Bitrix\Main\Application::getConnection();
    $connection->queryExecute($sqlQuery);

}*/