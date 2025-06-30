<?php

namespace EventsHandlers;

use Bitrix\Main\Loader;
use Bitrix\Iblock\IblockTable;
use Bitrix\Main\Diag\Debug;
use Bitrix\Crm\Entity\Deal;
use \Bitrix\Crm\Service\Container;
use Bitrix\Crm;

class OnAfterIBlockElementUpdateHandler
{


    /**
     * @param   &$arFields
     * @return void
     */
   public static function OnAfterIBlockElementUpdateHandler(&$arFields)
    {
        if ( Loader::includeModule('crm')) {
            $arFieldsIblockID = $arFields['IBLOCK_ID'];
            $iblockCode = getIblockCodeHandler($arFieldsIblockID);
            $iblockCodeOpt = 'request';

            if ($iblockCode && $iblockCode == $iblockCodeOpt) {

                $dealId = (int)$arFields["PROPERTY_VALUES"][75]["70:75"]["VALUE"];

                $strDealSumma =$arFields["PROPERTY_VALUES"][67]["70:67"]["VALUE"];


                $dealFactory = Container::getInstance()->getFactory(\CCrmOwnerType::Deal);
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
    }

}