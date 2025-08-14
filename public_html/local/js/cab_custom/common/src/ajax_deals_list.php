<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Crm\DealTable;
use Bitrix\Main\Loader;
use Bitrix\Main\Entity;
use Lab\Crmcustomtab\Orm\GarageTable;

Loader::includeModule('crm');
Loader::includeModule('lab.crmcustomtab');

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

if ($request->isAjaxRequest() && $_REQUEST['act'] == 'getVinDeal') {

    $iGarageTblId = $_REQUEST['id'];
    $arVinDeal = GarageTable::getList([
        'filter' => ['ID' => $iGarageTblId],
        'select' => [
            'UF_CRM_DEAL_VIN',
        ],
        'order' => [],
    ]);
    if ($itemVinDeal = $arVinDeal->fetch()) {
        $VinDeal = $itemVinDeal;
    }
}
/*if ($_REQUEST['act'] == 'form') {
    $iGarageTableId = $_REQUEST['iGarageTableId'];
    $deals = DealTable::getList([
        'select' => ['ID', 'TITLE', 'UF_CRM_DEAL_GARAGE_TABLE_ITEM_ID'],
        'filter' => [
            '=UF_CRM_DEAL_GARAGE_TABLE_ITEM_ID' => $iGarageTableId
        ],
        'order' => ['ID' => 'DESC']
    ])->fetchAll();

    echo json_encode($deals);
}*/
$res = array(
    'html' => $arVinDeal,
);
echo \Bitrix\Main\Web\Json::encode( $VinDeal);
//echo  $VinDeal;
die();