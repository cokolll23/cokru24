<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

$context = \Bitrix\Main\Application::getInstance()->getContext();
$request = $context->getRequest();
$blIsAjaxRequest = $request->isAjaxRequest();

if ($blIsAjaxRequest && $_REQUEST['act']) {

    \Bitrix\Main\Loader::includeModule('iblock');

    if ($_REQUEST['act'] == 'add') {
        $arURLexploded = explode('/', $_REQUEST['docId']);
        $iblockId = $arURLexploded[3];// iblockId процедур
        $procId = $arURLexploded[6]; // id процедуры
        $iblockIdBron = 19;
        $pacientFIO =$_REQUEST['fio'];
        $strDateTime = $_REQUEST['date'];



        $el = new CIBlockElement;
        $PROP = array();
        $PROP[69] = $procId; //  Процедура id
        $PROP[70] = $pacientFIO;
        $PROP[74] = $strDateTime;

        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
            "IBLOCK_ID"      => $iblockIdBron,
            "PROPERTY_VALUES"=> $PROP,
            "NAME"           => $pacientFIO,
            "ACTIVE"         => "Y",            // активен
        );


        if($PRODUCT_ID = $el->Add($arLoadProductArray)){
            $arrRes = [
                "success" => $PRODUCT_ID,
                'fio'=>$pacientFIO,
                'procedura'=>$procId
            ];
        }else{
            $arrRes = [
                "error" => $el->LAST_ERROR,
                'fio'=>$pacientFIO,
                'procedura'=>$procId
            ];
        }

    }

    echo json_encode($arrRes);
}