<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Crm\DealTable;
use Bitrix\Main\Loader;
use Bitrix\Main\Entity;

Loader::includeModule('crm');
Loader::includeModule('lab.crmcustomtab');

ob_start();



$APPLICATION->IncludeComponent(
    "lab.crmcustomtab:deals.grid",
    "",
    array(

    ),
    false
);
$html = ob_get_contents();
ob_end_clean();
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
    'html' => $html,

);
//echo \Bitrix\Main\Web\Json::encode($res);
echo $html;
die();