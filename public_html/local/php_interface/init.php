<?php
use Bitrix\Main\Loader;
use Bitrix\Iblock\IblockTable;
use Bitrix\Main\Diag\Debug;
use Bitrix\Main\Page\Asset;
use \Bitrix\Crm\Service\Container;

// todo start сделать в модуле use Bitrix\Main\EventManager;
/*//
//// При установке модуля добавляем обработчик
//RegisterModule("your.module.name");
//
//$eventManager = EventManager::getInstance();
//$eventManager->registerEventHandler(
//    "main",          // Модуль, где вешаем обработчик
//    "OnProlog",      // Событие (вызывается перед выводом HTML)
//    "your.module.name",  // Ваш модуль
//    "\\Your\\Module\\ExtensionLoader",  // Класс-обработчик
//    "loadCustomExtensions"  // Метод
//);*/
/*namespace Your\Module;

use Bitrix\Main\UI\Extension;

class ExtensionLoader
{
    public static function loadCustomExtensions()
    {
        Extension::load('cab_custom.checkdeals');
    }
}*/

//\Bitrix\Main\UI\Extension::load('cab_log_events.common'); // вывод js событий
CUtil::InitJSCore(array('jquery3', 'popup', 'ajax', 'date'));
\Bitrix\Main\UI\Extension::load('cab_custom.common');
\Bitrix\Main\UI\Extension::load('cab_custom.checkdeals');

// todo End

if (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
    require_once __DIR__ . '/../../vendor/autoload.php';
}
if (file_exists(__DIR__ . '/../app/autoloader.php')) {
    require_once __DIR__ . '/../app/autoloader.php';
}
if (file_exists(__DIR__ . '/../include/functions/pretty_print.php')) {
    require_once __DIR__ . '/../include/functions/pretty_print.php';
}
include_once __DIR__ . '/classes/Dadata.php';

$eventManager = \Bitrix\Main\EventManager::getInstance();

// после изменения записи в сделке
/*$eventManager->addEventHandlerCompatible("crm", "OnAfterCrmDealUpdate",'OnAfterCrmDealUpdateHandler');
$eventManager->addEventHandler("iblock", "OnAfterIBlockElementUpdate",'OnAfterIBlockElementUpdateHandler');
$eventManager->addEventHandlerCompatible("crm", "OnAfterCrmDealAdd",'OnAfterCrmDealAddHandler');
*/

// для создания кастомных свойств
$eventManager->addEventHandler('iblock', 'OnIBlockPropertyBuildList', ['UserTypes\SignUpForProcedure', 'GetUserTypeDescription']);
$eventManager->addEventHandler('iblock', 'OnIBlockPropertyBuildList', ['UserTypes\SelectDeal', 'GetUserTypeDescription']);

//$eventManager->addEventHandler("iblock", "OnAfterIBlockElementUpdate",['\EventsHandlers\OnAfterIBlockElementUpdateHandler', 'OnAfterIBlockElementUpdateHandler']);
//$eventManager->addEventHandlerCompatible("crm", "OnBeforeCrmDealUpdate", ['\EventsHandlers\OnBeforeCrmDealUpdateHandler','OnBeforeCrmDealUpdateHandler']);

// Обработчик для добавления активности
$eventManager->AddEventHandler('crm', 'onCrmActivityUpdate', ['EventsHandlers\updateFromActivity', 'updateFromActivity']);

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
