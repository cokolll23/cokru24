<?php
//require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

$params = [
    'num' => 10,       // сколько чисел нам нужно
    'min' => 0,          // минимальное значение (можно изменить)
    'max' => 10,       // максимальное значение (можно изменить)
    'col' => 1,          // количество колонок
    'base' => 10,         // система счисления (10 - десятичная)
    'format' => 'plain',    // формат ответа
    'rnd' => 'new'       // гарантия случайности
];

$url = 'https://www.random.org/integers/?' . http_build_query($params);

$response = file_get_contents($url);

if ($response === false) {
    // Обработка ошибки
    $log = date('Y-m-d H:i:s') . ' Не удалось получить данные с random.org ' . $url;
    file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/log.txt', $log . PHP_EOL, FILE_APPEND);
    die('Не удалось получить данные с random.org');
}
// Преобразуем ответ в массив чисел
$randomNumbers = explode("\n", trim($response));


//Bitrix\Main\Diag\Debug::dumpToFile($randomNumbers, 'randomNumbers '. $url .' ' . date('d-m-Y; H:i:s'));


$log = date('Y-m-d H:i:s') . ' ' . json_encode($randomNumbers, JSON_UNESCAPED_UNICODE);
file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/log.txt', $log . PHP_EOL, FILE_APPEND);

$strGetFileNumbs = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/log.txt');

echo json_encode($randomNumbers, JSON_UNESCAPED_UNICODE);




