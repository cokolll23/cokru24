<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';
require_once (__DIR__.'/crest.php');
//Bitrix\Main\Diag\Debug::dumpToFile($_REQUEST, '$arFields ' . date('d-m-Y; H:i:s'));

if (empty($_REQUEST['event'])){?>

    <div>Приложение используется как Обработчик события</div>

<?php

}
if ($_REQUEST['event'] === 'onCrmActivityAdd'){

    $activityId = $_REQUEST['data']['fields']['id'];
    //todo get activity info

    $result = CRest::call(
        'crm.activity.get',
        [
                'id' => $activityId
        ]
    );
    echo '<pre>';
    print_r($result);
    echo '</pre>';

}

$result = CRest::call('profile');

echo '<pre>';
	print_r($result);
echo '</pre>';
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';