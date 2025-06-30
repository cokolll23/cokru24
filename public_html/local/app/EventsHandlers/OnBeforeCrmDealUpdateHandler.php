<?php

namespace EventsHandlers;

use Bitrix\Main\Loader;
use Bitrix\Iblock\IblockTable;
use Bitrix\Main\Diag\Debug;
use Bitrix\Iblock\ElementTable;
use \Bitrix\Crm\Service\Container;

class OnBeforeCrmDealUpdateHandler
{
   public static function OnBeforeCrmDealUpdateHandler(&$arFields)
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

    }
}