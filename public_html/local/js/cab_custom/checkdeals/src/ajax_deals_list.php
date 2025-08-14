<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Crm\DealTable;
use Bitrix\Main\Loader;
use Bitrix\Main\Entity;
use Bitrix\Main\Application;


Loader::includeModule('crm');
/*
[result] => Array
(
    [0] => Array
    (
        [NAME] => Новая
        [SORT] => 10
                    [STATUS_ID] => NEW
                )

            [1] => Array
(
    [NAME] => Подготовка документов
[SORT] => 20
                    [STATUS_ID] => PREPARATION
                )

            [2] => Array
(
    [NAME] => Cчёт на предоплату
[SORT] => 30
                    [STATUS_ID] => PREPAYMENT_INVOICE
                )

            [3] => Array
(
    [NAME] => В работе
[SORT] => 40
                    [STATUS_ID] => EXECUTING
                )

            [4] => Array
(
    [NAME] => Финальный счёт
[SORT] => 50
                    [STATUS_ID] => FINAL_INVOICE
                )

            [5] => Array
(
    [NAME] => Сделка успешна
[SORT] => 60
                    [STATUS_ID] => WON
                )

            [6] => Array
(
    [NAME] => Сделка провалена
[SORT] => 70
                    [STATUS_ID] => LOSE
                )

            [7] => Array
(
    [NAME] => Анализ причины провала
[SORT] => 80
                    [STATUS_ID] => APOLOGY
                )*/


$request = Application::getInstance()->getContext()->getRequest();

if ($request->isAjaxRequest() && $_REQUEST['act'] == 'checkDeals') {

    $vinVal = $_REQUEST['vinVal'];

    $dbDeal = CCrmDeal::GetListEx(
        array("ID" => "ASC"),
        array("UF_CRM_DEAL_VIN" => $vinVal),
        false,
        false,
        array("ID", 'TITLE',
            'STAGE_ID'),
        array());

    while ($arDeal = $dbDeal->fetch()) {

        $CCrmDeal[] = $arDeal;
    }
    //   ob_start();

    $dealsId = DealTable::getList([
        'filter' => ['UF_CRM_DEAL_VIN' => (string)$vinVal],
        'select' => [
            'ID',
            'TITLE',
            'STAGE_ID',
            'UF_CRM_DEAL_VIN',
        ],
        //'order' => $sort['sort'],
    ])->fetchAll();
}
if (count($dealsId) > 0) {

    foreach ($dealsId as $dealId) {
        if ($dealId['STAGE_ID'] === 'WON' || $dealId['STAGE_ID'] === 'LOSE') {
           $arWon[]=1;
        } else {
            $arWon[]=0;

        }
    }
    if (in_array(0, $arWon, true)) {
        $blY = 0;
    } else {
        $blY = 1;
    }

} else {
    $blY = 1;
}


/*$html = ob_get_contents();
ob_end_clean();*/


$res = array(
    '$CCrmDeal' => $CCrmDeal,
    '$dealsId' => $dealsId,
    'res' => $blY,

);
echo \Bitrix\Main\Web\Json::encode($res);

die();