<?php
require_once (__DIR__.'/crest.php');


$log = date('Y-m-d H:i:s') . ' ' . print_r($_REQUEST, true);
file_put_contents(__DIR__ . '/log.txt', $log . PHP_EOL, FILE_APPEND);

if($_REQUEST['event'])
{

}

$result = CRest::call('profile');

echo '<pre>';
	print_r($result);
echo '</pre>';
