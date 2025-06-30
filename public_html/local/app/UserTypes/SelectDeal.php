<?php

namespace UserTypes;

use Bitrix\Iblock;
use Bitrix\Main\Loader;
use Bitrix\Crm;

Loader::includeModule('iblock');
Loader::includeModule('crm');


class SelectDeal
{
    /** @deprecated */
    public const USER_TYPE = Iblock\PropertyTable::USER_TYPE_ELEMENT_LIST;

    public static function GetUserTypeDescription()
    {

        // PROPERTY_TYPE:
// \Bitrix\Iblock\PropertyTable::TYPE_STRING - строка
// \Bitrix\Iblock\PropertyTable::TYPE_NUMBER - число
// \Bitrix\Iblock\PropertyTable::TYPE_LIST - список
// \Bitrix\Iblock\PropertyTable::TYPE_ELEMENT - привязка к элементу
// \Bitrix\Iblock\PropertyTable::TYPE_SECTION - привязка к разделу
// \Bitrix\Iblock\PropertyTable::TYPE_FILE - файл
        /* Строка - S
            Число - N
            Список - L
            Файл - F
            Привязка к элементам - E
            Привязка к разделам - G*/


        // примеры USER_TYPE:

// grain_link - универсальная привязка
// gtable - свойство-таблиц
//// HTML - текст/html
// video - видео
// Date - дата
// DateTime - дата/время
// Money - деньги
// SKU - привязка к предложениям в магазине
// FileMan - привязка к файлу на сервере

        return [
            'PROPERTY_TYPE' => Iblock\PropertyTable::TYPE_STRING,
            /*    PROPERTY_TYPE:
     \Bitrix\Iblock\PropertyTable::TYPE_STRING - строка
     \Bitrix\Iblock\PropertyTable::TYPE_NUMBER - число
     \Bitrix\Iblock\PropertyTable::TYPE_LIST - список
     \Bitrix\Iblock\PropertyTable::TYPE_ELEMENT - привязка к элементу
     \Bitrix\Iblock\PropertyTable::TYPE_SECTION - привязка к разделу
     \Bitrix\Iblock\PropertyTable::TYPE_FILE - файл
                 Строка - S
                    Число - N
                    Список - L
                    Файл - F
                    Привязка к элементам - E
                    Привязка к разделам - G*/

            'USER_TYPE' => 'selectdeal',
            'DESCRIPTION' => "Привязка к сделкам в виде списка",
            'GetPropertyFieldHtml' => [__CLASS__, 'GetPropertyFieldHtml'],
            'GetPublicEditHtml' => [__CLASS__, 'GetPublicEditHtml'],
           // 'GetPublicViewHTML' => [__CLASS__, 'GetPublicViewHTML'],

        ];
    }

    public static function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName)
    {
        $deals = static::getOptionsCrmDealsHtml();
        $options = '';
        // $bWasSelect = false;
        foreach ($deals as $deal) {
            /*$options .= '<option value="'.htmlspecialchars($deal['ID']).'">' . htmlspecialchars($deal['TITLE']) . '</option>';*/
            $options .= '<option value="' . $deal["ID"] . '"';
            if (in_array($deal["ID"], $value)) {
                $options .= ' selected';
            }
            $options .= '>' . htmlspecialchars($deal['TITLE']) . '</option>';
        }

        $html = '<select name="' . $strHTMLControlName["VALUE"] . '">';

        $arProperty['IS_REQUIRED'] ??= 'N';
        if ($arProperty['IS_REQUIRED'] !== 'Y') {
            $html .= '<option value="">' . "(не установлено)" . '</option>';
        }
        $html .= $options;
        $html .= '</select>';
        return $html;
    }



    /**
     * Returns custom field for list and edit pages in public area.
     * @param array $arProperty Property data.
     * @param array $arValue Property value data.
     * @param array $strHTMLControlName Html input data.
     * @return string
     */
    public static function getPublicEditHtml(array $arProperty, array $arValue, array $strHTMLControlName): string
    {
        $strID = preg_replace('/[^a-zA-Z0-9_]/i', 'x', $strHTMLControlName["VALUE"]);
        $value = htmlspecialcharsbx(trim($arValue['VALUE']));
        $deals = static::getOptionsCrmDealsHtml();
        $options = '';
        // $bWasSelect = false;
        foreach ($deals as $deal) {
            /*$options .= '<option value="'.htmlspecialchars($deal['ID']).'">' . htmlspecialchars($deal['TITLE']) . '</option>';*/
            $options .= '<option value="' . $deal["ID"] . '"';
            if (in_array($deal["ID"], $value)) {
                $options .= ' selected';
                // $bWasSelect = true;
            }
            $options .= '>' . htmlspecialchars($deal['TITLE']) . '</option>';
        }

        $html = '<select name="' . $strHTMLControlName["VALUE"] . '">';
        $arProperty['IS_REQUIRED'] ??= 'N';
        if ($arProperty['IS_REQUIRED'] !== 'Y') {
            $html .= '<option value="">' . "(не установлено)" . '</option>';
        }
        $html .= $options;
        $html .= '</select>';

        return $html;
    }

    public static function getOptionsCrmDealsHtml()
    {

        if (!Loader::includeModule('crm')) {
            return 'Модуль CRM не загружен.';
        }
        $deals = [];
        $dbResult = Crm\DealTable::getList([
            'filter' => [],
            'select' => ['ID', 'TITLE'], // Укажите нужные поля
            'order' => ['DATE_CREATE' => 'DESC'], // Сортировка по дате создания
        ]);

        while ($deal = $dbResult->fetch()) {
            $deals[] = $deal;
        }
        if (empty($deals)) {
            return '<p>Сделки не найдены.</p>';
        }
        return $deals;
    }


}

