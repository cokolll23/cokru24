<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Config\Option;
use Bitrix\Main\HttpApplication;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
/**
 * @global CMain $APPLICATION
 */

$request = HttpApplication::getInstance()->getContext()->getRequest();

Loader::includeModule('main');

$module_id = htmlspecialcharsbx('' != $request['mid'] ? $request['mid'] : $request['id']);

Loader::includeModule($module_id);
$aTabsStatic = [
    [
        'DIV' => 'crm',
        'TAB' => Loc::getMessage('EMPTY_CONFIG_TITLE'),
        'ICON' => 'dis_settings',
        'TITLE' => Loc::getMessage('EMPTY_CONFIG_TITLE'),
        'OPTIONS' => [
            [
                'EMPTY_CONFIG',
                Loc::getMessage('EMPTY_CONFIG_TITLE'),
                '',
                [
                    'text',
                    50,
                ],
            ],
        ]
    ],
];

$aTabs = $aTabsStatic;

if ($request->isPost() && check_bitrix_sessid()) {

    foreach ($aTabs as $aTab) {

        foreach ($aTab['OPTIONS'] as $arOption) {

            if (!is_array($arOption)) {
                continue;
            }

            if ($arOption['note']) {

                continue;
            }

            $optionName = $arOption[0];
            if ($request['apply']) {

                $optionValue = $request->getPost($optionName);
                if (in_array($optionName, ['ACTIVE'], true)) {
                    if ('' == $optionValue) {
                        $optionValue = 'N';
                    }
                }

                if (!empty($optionValue || 0 === $optionValue)) {
                    Option::set($module_id, $optionName, is_array($optionValue) ? implode(',', $optionValue) : $optionValue);
                } else {
                    Option::delete($module_id, ['name' => $optionName]);
                }

            } elseif ($request['default']) {
                Option::set($module_id, $optionName, $arOption[2]);
            }
        }
    }

    LocalRedirect($APPLICATION->GetCurPage() . '?mid=' . $module_id . '&lang=' . LANG);
} ?>

<?php
$tabControl = new CAdminTabControl(
    'tabControl',
    $aTabs,
    false
);

$tabControl->Begin();
?>
<form action='<?php echo($APPLICATION->GetCurPage()); ?>?mid=<?php echo($module_id); ?>&lang=<?php echo(LANG); ?>'
      method='post'>

    <?php

    foreach ($aTabs as $aTab) {

        $tabControl->BeginNextTab();
        if ($aTab['OPTIONS']) {
            __AdmSettingsDrawList($module_id, $aTab['OPTIONS']);
        }
    }
    $tabControl->Buttons();
    ?>

    <input type='submit' name='apply' value='<?= Loc::getMessage('MAIN_SAVE') ?>' class='adm-btn-save'/>

    <?php
    echo(bitrix_sessid_post());
    ?>

</form>
<?php $tabControl->End(); ?>
