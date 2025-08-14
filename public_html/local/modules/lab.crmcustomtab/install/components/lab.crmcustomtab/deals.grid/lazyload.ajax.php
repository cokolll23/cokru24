<?php

use Bitrix\Main\Application;
use Bitrix\Main\Loader;

define('NO_KEEP_STATISTIC', 'Y');
define('NO_AGENT_STATISTIC', 'Y');
define('NO_AGENT_CHECK', true);
define('PUBLIC_AJAX_MODE', true);
define('DisableEventsCheck', true);

$siteId = isset($_REQUEST['site']) ? mb_substr(preg_replace('/[^a-z0-9_]/i', '', $_REQUEST['site']), 0, 2) : '';

if ($siteId !== '') {
    define('SITE_ID', $siteId);
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!check_bitrix_sessid()) {
    die();
}

Header('Content-Type: text/html; charset=' . LANG_CHARSET);

Loader::includeModule('crm');

global $APPLICATION;
$APPLICATION->ShowAjaxHead();

$request = Application::getInstance()->getContext()->getRequest();

$componentData = $request->get('PARAMS');

if(is_array($componentData)){
    $componentParams = isset($componentData['params']) && is_array($componentData['params']) ? $componentData['params'] : [];
}

$server = $request->getServer();

$ajaxLoaderParams = [
    'url' => $server->get('REQUEST_URI'),
    'method' => 'POST',
    'dataType' => 'ajax',
    'data' => [
        'PARAMS' => $componentData,
    ]
];

$componentParams['AJAX_LOADER'] = $ajaxLoaderParams;
\Bitrix\Main\Diag\Debug::dumpToFile($componentParams);
$APPLICATION->IncludeComponent(
    'bitrix:ui.sidepanel.wrapper',
    '',
    [
        'PLAIN_VIEW' => false,
        'USE_PADDING' => true,
        'POPUP_COMPONENT_NAME' => 'lab.crmcustomtab:deals.grid',
        'POPUP_COMPONENT_TEMPLATE_NAME' => $componentData['template'] ?? '',
        'POPUP_COMPONENT_PARAMS' => $componentParams,
    ],
);

\CMain::FinalActions();