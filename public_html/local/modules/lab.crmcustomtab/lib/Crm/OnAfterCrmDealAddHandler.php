<?php

namespace Lab\Crmcustomtab\Crm;

use Bitrix\Main\Loader;
use Bitrix\Iblock\IblockTable;
use Bitrix\Main\Diag\Debug;
use Bitrix\Iblock\ElementTable;
use \Bitrix\Crm\Service\Container;
use Lab\Crmcustomtab\Orm\GarageTable;
use Relay\Event;
use Bitrix\Crm\DealTable;
use Bitrix\Main\Entity;


Loader::includeModule('iblock');
Loader::includeModule('crm');
class OnAfterCrmDealAddHandler
{
    public static function OnAfterCrmDealAddHandler (&$arFields)
    {
        $result = GarageTable::add([
            'CONTACT_ID' => $arFields["CONTACT_ID"],
            //'MARKA' => $arFields["UF_CRM_DEAL_MARKA"],
            'MARKA' => $arFields["TITLE"],
            'MODEL' => $arFields["UF_CRM_DEAL_MODEL"],
            'YEAR' => $arFields["UF_CRM_DEAL_YEAR"],
            'COLOR' => $arFields["UF_CRM_DEAL_COLOR"],
            'MILEAGE' => $arFields["UF_CRM_DEAL_MILEAGE"],
            'UF_CRM_DEAL_VIN' => $arFields["UF_CRM_DEAL_VIN"],
        ]);




       \Bitrix\Main\Diag\Debug::dumpToFile($arFields, '$arFields Deal ' .__DIR__ .' ; '. date('d-m-Y; H:i:s'));

       /* $log = date('Y-m-d H:i:s') . ' ' . print_r($_REQUEST, true);
        file_put_contents(__DIR__ . '/log.txt', $log . PHP_EOL, FILE_APPEND);*/

        if ($result->isSuccess()) {
            $idItemId = $result->getId();
            $dealId = $arFields['ID'];

            // Обновление поля
            $result = DealTable::update($dealId, [
                'UF_CRM_DEAL_GARAGE_TABLE_ITEM_ID' => $idItemId
            ]);

            if ($result->isSuccess()) {
                $updateStatus =  "Поле UF_CRM_DEAL_GARAGE_TABLE_ITEM_ID успешно обновлено";
            } else {
                $updateStatus =  "Ошибка: " . implode(', ', $result->getErrorMessages());
            }
           // \Bitrix\Main\Diag\Debug::dumpToFile($updateStatus, '$arFields ' .__DIR__ .' ; '. date('d-m-Y; H:i:s'));



        } else {
            $res = $result->getErrorMessages();
        }

    }

}