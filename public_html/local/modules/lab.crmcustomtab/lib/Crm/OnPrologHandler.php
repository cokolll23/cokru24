<?php

namespace Lab\Crmcustomtab\Crm;

use Bitrix\Main\UI\Extension;

class OnPrologHandler
{
    public static function OnPrologHandler()
    {
        // Проверяем, зарегистрировано ли расширение
        if (self::isExtensionRegistered('cab_custom.checkdeals')) {
            Extension::load('cab_custom.checkdeals');
        }
        if (self::isExtensionRegistered('cab_custom.common')) {
            Extension::load('cab_custom.common');
        }
    }

    protected static function isExtensionRegistered($extensionName)
    {
        $config = Configuration::getInstance()->get('ui.extension');
        return isset($config[$extensionName]);
    }

}