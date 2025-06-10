<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$context = \Bitrix\Main\Application::getInstance()->getContext();
$request = $context->getRequest();
$blIsAjaxRequest = $request->isAjaxRequest();

if ($blIsAjaxRequest && $_REQUEST['atr']){
    echo json_encode($_REQUEST);

}