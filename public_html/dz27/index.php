<?php
//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once (__DIR__.'/crest.php');
echo 4444;
if(in_array($_REQUEST['event'], ['0' => 'ONCRMCONTACTUPDATE', '1' => 'ONCRMCONTACTADD']))
{
   // Bitrix\Main\Diag\Debug::dumpToFile($_REQUEST, '$arFields ' . date('d-m-Y; H:i:s'));

}


