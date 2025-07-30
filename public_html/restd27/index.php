<?php
require_once (__DIR__.'/crest.php');

if($_REQUEST['event'] === 'ONCRMACTIVITYADD')
{

    $intActivityId = $_REQUEST['data']['FIELDS']['ID'];
    $url = 'https://cokru.ru/rest/1/tkrjgnjygf10nfzp/crm.activity.get.json?ID=' . $intActivityId;

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $url,
    ));
    $result = json_decode(curl_exec($curl), 1);

    curl_close($curl);

    $intContactID=$result['result']['OWNER_ID'];
    $intDateLast=$result['result']['CREATED'];

    $resUpdate = CRest::call(
        'crm.contact.update',
        [
            'id' => $intContactID,
            'fields' => [
                'UF_CRM_CONTACT_LAST_COMMUNICATION' =>$intDateLast//date('Y-m-d H:i:s'), //'2023-12-31T23:59:59+03:00'//$intDateLast
            ]
        ]
    );

//$log = date('Y-m-d H:i:s') . '--OWNER_ID-- ' . $result['result']['OWNER_ID'] ;
    $log = date('Y-m-d H:i:s') . '; $resUpdate ; ' . print_r($resUpdate, true);
    file_put_contents(__DIR__ . '/log.txt', $log . PHP_EOL, FILE_APPEND);


}
