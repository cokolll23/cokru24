<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Компонент списка таблицы базы данных");

// Dadata
$token = "0c825d0906122684951a7a3d60ee8848289d4344"; // ваш api ключ
$secret = "db2700343995d8f5e1992e0fcbd81ded70267e71"; // ваш секретный ключ

$dadata = new Dadata($token, $secret);
$dadata->init();

// Найти компанию по ИНН
$fields = array("query" => "7707083893", "count" => 5);
$result = $dadata->suggest("party", $fields);
dump($result);


require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");