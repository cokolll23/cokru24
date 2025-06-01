<?php

use Bitrix\Main\Loader;
use Bitrix\Iblock\IblockTable;
use Bitrix\Main\Diag\Debug;

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
$eventManager->addEventHandler("iblock", "OnAfterIBlockElementAdd", 'OnAfterIBlockElementAddHandler');

function OnAfterIBlockElementAddHandler(&$arFields)
{
    if (Loader::includeModule('iblock') && Loader::includeModule('crm')) {
        $result = IblockTable::getList(array(
            'filter' => ['ID' => $arFields['IBLOCK_ID']],
            'select' => ['CODE']
        ));
        if ($iblock = $result->fetch()) {
            $iblockCode = $iblock['CODE'];
        } else {
            echo "Инфоблок с ID " . $arFields['ID'] . " не найден.";
        }
    } else {
        echo "Модуль инфоблоков не подключен.";

    }
    $iblockCodeOpt = 'request';
    if ($iblockCode == $iblockCodeOpt) {
       /* dump($arFields);
        die();*/
       /* $dealFactory = \Bitrix\Crm\Service\Container::getInstance()->getFactory(CCrmOwnerType::Deal);

        $newDealItem = $dealFactory->createItem();

        $newDealItem->set('TITLE', 'Тестовая сделка D7');
        $dealAddOperation = $dealFactory->getAddOperation($newDealItem);
        $addResult = $dealAddOperation->launch();*/

        /*  "PROPERTY_VALUES" => array:2 [▼
      68 => array:1 [▼
        "n0" => array:1 [▼
          "VALUE" => "Иванов"
        ]
      ]
      67 => array:1 [▼
        "n0" => array:1 [▼
          "VALUE" => "12000"
        ]
      ]
    ] */
    }
}