<?php

namespace ScriptClasses;

class ExecuteDailyTask
{
    public static function getRandomNumbers()
    {

        $params = [
            'num'    => 10,       // сколько чисел нам нужно
            'min'    => 0,          // минимальное значение (можно изменить)
            'max'    => 10,       // максимальное значение (можно изменить)
            'col'    => 1,          // количество колонок
            'base'   => 10,         // система счисления (10 - десятичная)
            'format' => 'plain',    // формат ответа
            'rnd'    => 'new'       // гарантия случайности
        ];

        $url = 'https://www.random.org/integers/?' . http_build_query($params);

        $response = file_get_contents($url);

        if ($response === false) {
            // Обработка ошибки
            die('Не удалось получить данные с random.org');
        }
        // Преобразуем ответ в массив чисел
        $randomNumbers = explode("\n", trim($response));
        Bitrix\Main\Diag\ Debug::dumpToFile($randomNumbers, '$randomNumbers ' . date('d-m-Y; H:i:s'));


        $log =  print_r(json_encode($randomNumbers), true);
        file_put_contents( $_SERVER['DOCUMENT_ROOT'].'/log.txt', $log );

      echo $strGetFileNumbs = file_get_contents( $_SERVER['DOCUMENT_ROOT'].'/log.txt' );

      // return json_decode($strGetFileNumbs);

    }
}