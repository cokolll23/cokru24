<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

include_once('crest.php');

if($_REQUEST['event'] == 'ONCRMACTIVITYADD' || $_REQUEST['event'] == 'ONCRMACTIVITYADD')
{

    $log = basename(__FILE__, '.php').' , '.$_REQUEST['data']['FIELDS']['ID'].'-'.date('Y-m-d H:i:s') . ' ' . print_r($_REQUEST, true);
    file_put_contents(__DIR__ . '/log.txt', $log . PHP_EOL, FILE_APPEND);


}



