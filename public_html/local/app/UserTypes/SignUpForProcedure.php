<?php

namespace UserTypes;

use Bitrix\Iblock;

\Bitrix\Main\Loader::includeModule('iblock');




class SignUpForProcedure
{
    /** @deprecated */
    public const USER_TYPE = Iblock\PropertyTable::USER_TYPE_ELEMENT_LIST;

    public static function GetUserTypeDescription()
    {
        return [
            'PROPERTY_TYPE' => Iblock\PropertyTable::TYPE_ELEMENT,
            'USER_TYPE' => Iblock\PropertyTable::USER_TYPE_ELEMENT_LIST,
            'DESCRIPTION' => "Привязка к элементам в виде списка с записью",
            'GetPropertyFieldHtml' => [__CLASS__, 'GetPropertyFieldHtml'],
            'GetPropertyFieldHtmlMulty' => [__CLASS__, 'GetPropertyFieldHtmlMulty'],
            'GetPublicEditHTML' => [__CLASS__, 'GetPropertyFieldHtml'],
            'GetPublicEditHTMLMulty' => [__CLASS__, 'GetPropertyFieldHtmlMulty'],
            'GetPublicViewHTML' => [__CLASS__, 'GetPublicViewHTML'],
            'GetUIFilterProperty' => [__CLASS__, 'GetUIFilterProperty'],
            'GetAdminFilterHTML' => [__CLASS__, 'GetAdminFilterHTML'],
            'PrepareSettings' => [__CLASS__, 'PrepareSettings'],
            'GetSettingsHTML' => [__CLASS__, 'GetSettingsHTML'],
            'GetExtendedValue' => [__CLASS__, 'GetExtendedValue'],
            'GetUIEntityEditorProperty' => [__CLASS__, 'GetUIEntityEditorProperty'],
        ];
    }

    public static function PrepareSettings($arProperty)
    {
        $size = (int)($arProperty['USER_TYPE_SETTINGS']['size'] ?? 0);
        if ($size <= 0) {
            $size = 1;
        }

        $width = (int)($arProperty['USER_TYPE_SETTINGS']['width'] ?? 0);
        if ($width <= 0) {
            $width = 0;
        }

        $group = ($arProperty['USER_TYPE_SETTINGS']['group'] ?? 'N');
        $group = ($group === 'Y' ? 'Y' : 'N');

        $multiple = ($arProperty['USER_TYPE_SETTINGS']['multiple'] ?? 'N');
        $multiple = ($multiple === 'Y' ? 'Y' : 'N');

        return [
            'size' => $size,
            'width' => $width,
            'group' => $group,
            'multiple' => $multiple,
        ];
    }

    public static function GetSettingsHTML($arProperty, $strHTMLControlName, &$arPropertyFields)
    {
        $settings = SignUpForProcedure::PrepareSettings($arProperty);

        $arPropertyFields = [
            'HIDE' => [
                'ROW_COUNT',
                'COL_COUNT',
                'MULTIPLE_CNT',
            ],
        ];

        return '
		<tr valign="top">
			<td>' . "Высота списка" . ':</td>
			<td><input type="text" size="5" name="' . $strHTMLControlName["NAME"] . '[size]" value="' . $settings["size"] . '"></td>
		</tr>
		<tr valign="top">
			<td>' . "Ограничить по ширине (0 - не ограничивать)" . ':</td>
			<td><input type="text" size="5" name="' . $strHTMLControlName["NAME"] . '[width]" value="' . $settings["width"] . '">px</td>
		</tr>
		<tr valign="top">
			<td>' . "Группировать по разделам" . ':</td>
			<td><input type="checkbox" name="' . $strHTMLControlName["NAME"] . '[group]" value="Y" ' . ($settings["group"] == "Y" ? 'checked' : '') . '></td>
		</tr>
		<tr valign="top">
			<td>' . "Отображать в виде списка множественного выбора" . ':</td>
			<td><input type="checkbox" name="' . $strHTMLControlName["NAME"] . '[multiple]" value="Y" ' . ($settings["multiple"] == "Y" ? 'checked' : '') . '></td>
		</tr>
		';
    }

    //PARAMETERS:
    //$arProperty - b_iblock_property.*
    //$value - array("VALUE","DESCRIPTION") -- here comes HTML form value
    //strHTMLControlName - array("VALUE","DESCRIPTION")
    //return:
    //safe html
    public static function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName)
    {
        $settings = SignUpForProcedure::PrepareSettings($arProperty);
        if ($settings["size"] > 1)
            $size = ' size="' . $settings["size"] . '"';
        else
            $size = '';

        if ($settings["width"] > 0)
            $width = ' style="width:' . $settings["width"] . 'px"';
        else
            $width = '';

        $bWasSelect = false;
        $options = SignUpForProcedure::GetOptionsHtml($arProperty, array($value["VALUE"]), $bWasSelect);

        $html = '<select name="' . $strHTMLControlName["VALUE"] . '"' . $size . $width . '>';
        $arProperty['IS_REQUIRED'] ??= 'N';
        if ($arProperty['IS_REQUIRED'] !== 'Y') {
            $html .= '<option value=""' . (!$bWasSelect ? ' selected' : '') . '>' . "(не установлено)" . '</option>';
        }
        $html .= $options;
        $html .= '</select>';

        return $html;
    }

    public static function GetPropertyFieldHtmlMulty($arProperty, $value, $strHTMLControlName)
    {
        $max_n = 0;
        $values = array();
        if (is_array($value)) {
            foreach ($value as $property_value_id => $arValue) {
                if (is_array($arValue))
                    $values[$property_value_id] = $arValue["VALUE"];
                else
                    $values[$property_value_id] = $arValue;

                if (preg_match("/^n(\\d+)$/", $property_value_id, $match)) {
                    if ($match[1] > $max_n)
                        $max_n = intval($match[1]);
                }
            }
        }

        $settings = SignUpForProcedure::PrepareSettings($arProperty);
        if ($settings["size"] > 1)
            $size = ' size="' . $settings["size"] . '"';
        else
            $size = '';

        if ($settings["width"] > 0)
            $width = ' style="width:' . $settings["width"] . 'px"';
        else
            $width = '';

        if ($settings["multiple"] == "Y") {
            $bWasSelect = false;
            $options = SignUpForProcedure::GetOptionsHtml($arProperty, $values, $bWasSelect);

            $html = '<input type="hidden" name="' . $strHTMLControlName["VALUE"] . '[]" value="">';
            $html .= '<select multiple name="' . $strHTMLControlName["VALUE"] . '[]"' . $size . $width . '>';
            if ($arProperty["IS_REQUIRED"] != "Y")
                $html .= '<option value=""' . (!$bWasSelect ? ' selected' : '') . '>' . "(не установлено)" . '</option>';
            $html .= $options;
            $html .= '</select>';
        } else {
            if (end($values) != "" || mb_substr((string)key($values), 0, 1) != "n")
                $values["n" . ($max_n + 1)] = "";

            $name = $strHTMLControlName["VALUE"] . "VALUE";

            $html = '<table cellpadding="0" cellspacing="0" border="0" class="nopadding" width="100%" id="tb' . md5($name) . '">';
            foreach ($values as $property_value_id => $value) {
                $html .= '<tr><td>';

                $bWasSelect = false;
                $options = SignUpForProcedure::GetOptionsHtml($arProperty, array($value), $bWasSelect);

                $html .= '<select name="' . $strHTMLControlName["VALUE"] . '[' . $property_value_id . '][VALUE]"' . $size . $width . '>';
                $html .= '<option value=""' . (!$bWasSelect ? ' selected' : '') . '>' . "(не установлено)" . '</option>';
                $html .= $options;
                $html .= '</select>';

                $html .= '</td></tr>';
            }
            $html .= '</table>';

            $html .= '<input type="button" value="' . "Добавить" . '" onClick="BX.IBlock.Tools.addNewRow(\'tb' . md5($name) . '\', -1)">';
        }

        return $html;
    }

    public static function GetAdminFilterHTML($arProperty, $strHTMLControlName)
    {
        $lAdmin = new CAdminList($strHTMLControlName["TABLE_ID"]);
        $lAdmin->InitFilter(array($strHTMLControlName["VALUE"]));
        $filterValue = $GLOBALS[$strHTMLControlName["VALUE"]];

        if (isset($filterValue) && is_array($filterValue))
            $values = $filterValue;
        else
            $values = array();

        $settings = SignUpForProcedure::PrepareSettings($arProperty);
        if ($settings["size"] > 1)
            $size = ' size="' . $settings["size"] . '"';
        else
            $size = '';

        if ($settings["width"] > 0)
            $width = ' style="width:' . $settings["width"] . 'px"';
        else
            $width = '';

        $bWasSelect = false;
        $options = SignUpForProcedure::GetOptionsHtml($arProperty, $values, $bWasSelect);

        $html = '<select multiple name="' . $strHTMLControlName["VALUE"] . '[]"' . $size . $width . '>';
        $html .= '<option value=""' . (!$bWasSelect ? ' selected' : '') . '>' . "(любой)" . '</option>';
        $html .= $options;
        $html .= '</select>';
        return $html;
    }

    public static function GetUIFilterProperty($arProperty, $strHTMLControlName, &$fields)
    {
        $fields['type'] = 'list';
        $fields['items'] = self::getItemsForUiFilter($arProperty);
        $fields['operators'] = array(
            'default' => '=',
            'enum' => '@',
        );
    }

    private static function getItemsForUiFilter($arProperty)
    {
        $items = array();
        $settings = static::PrepareSettings($arProperty);

        if ($settings["group"] === "Y") {
            $arElements = SignUpForProcedure::GetElements($arProperty["LINK_IBLOCK_ID"]);
            $arTree = SignUpForProcedure::GetSections($arProperty["LINK_IBLOCK_ID"]);
            foreach ($arElements as $i => $arElement) {
                if (
                    $arElement["IN_SECTIONS"] == "Y"
                    && array_key_exists($arElement["IBLOCK_SECTION_ID"], $arTree)
                ) {
                    $arTree[$arElement["IBLOCK_SECTION_ID"]]["E"][] = $arElement;
                    unset($arElements[$i]);
                }
            }

            // todo add <optgroup> for ui filter
            foreach ($arTree as $arSection) {
                if (isset($arSection["E"])) {
                    foreach ($arSection["E"] as $arItem) {
                        $items[$arItem["ID"]] = $arItem["NAME"];
                    }
                }
            }
            foreach ($arElements as $arItem) {
                $items[$arItem["ID"]] = $arItem["NAME"];
            }

        } else {
            foreach (SignUpForProcedure::GetElements($arProperty["LINK_IBLOCK_ID"]) as $arItem) {
                $items[$arItem["ID"]] = $arItem["NAME"];
            }
        }

        return $items;
    }

    public static function GetPublicViewHTML($arProperty, $arValue, $strHTMLControlName)
    {

        static $cache = array();

        $strResult = '';
        $arValue['VALUE'] = intval($arValue['VALUE']);
        if (0 < $arValue['VALUE']) {
            $viewMode = '';
            $resultKey = '';
            if (!empty($strHTMLControlName['MODE'])) {
                switch ($strHTMLControlName['MODE']) {
                    case 'CSV_EXPORT':
                        $viewMode = 'CSV_EXPORT';
                        $resultKey = 'ID';
                        break;
                    case 'EXTERNAL_ID':
                        $viewMode = 'EXTERNAL_ID';
                        $resultKey = '~XML_ID';
                        break;
                    case 'SIMPLE_TEXT':
                        $viewMode = 'SIMPLE_TEXT';
                        $resultKey = '~NAME';
                        break;
                    case 'ELEMENT_TEMPLATE':
                        $viewMode = 'ELEMENT_TEMPLATE';
                        $resultKey = '~NAME';
                        break;
                    case 'BIZPROC':
                        $viewMode = 'BIZPROC';
                        break;
                }
            }

            if (!isset($cache[$arValue['VALUE']])) {
                $arFilter = [];
                $intIBlockID = (int)$arProperty['LINK_IBLOCK_ID'];
                if ($intIBlockID > 0)
                    $arFilter['IBLOCK_ID'] = $intIBlockID;
                $arFilter['ID'] = $arValue['VALUE'];
                if ($viewMode === '') {
                    $arFilter['ACTIVE'] = 'Y';
                    $arFilter['ACTIVE_DATE'] = 'Y';
                    $arFilter['CHECK_PERMISSIONS'] = 'Y';
                    $arFilter['MIN_PERMISSION'] = 'R';
                }
                $rsElements = \CIBlockElement::GetList(
                    array(),
                    $arFilter,
                    false,
                    false,
                    array("ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL")
                );
                if (isset($strHTMLControlName['DETAIL_URL'])) {
                    $rsElements->SetUrlTemplates($strHTMLControlName['DETAIL_URL']);
                }
                $cache[$arValue['VALUE']] = $rsElements->GetNext(true, true);
                unset($rsElements);
            }
            if (!empty($cache[$arValue['VALUE']]) && is_array($cache[$arValue['VALUE']])) {
                if ($viewMode !== '' && $resultKey !== '') {
                    $strResult .= $cache[$arValue['VALUE']][$resultKey];
                } else {
                    $strResult .= '<a href="' . $cache[$arValue['VALUE']]['DETAIL_PAGE_URL'] . '">' . $cache[$arValue['VALUE']]['NAME'] . '</a>';
                }
            }
        }

       // $strResult=$strResult ."<br>". $strResAfter;
        return $strResult;
    }

    public static function GetOptionsHtml($arProperty, $values, &$bWasSelect)
    {
        $options = "";
        $settings = SignUpForProcedure::PrepareSettings($arProperty);
        $bWasSelect = false;

        if ($settings["group"] === "Y") {
            $arElements = SignUpForProcedure::GetElements($arProperty["LINK_IBLOCK_ID"]);
            $arTree = SignUpForProcedure::GetSections($arProperty["LINK_IBLOCK_ID"]);
            foreach ($arElements as $i => $arElement) {
                if (
                    $arElement["IN_SECTIONS"] == "Y"
                    && array_key_exists($arElement["IBLOCK_SECTION_ID"], $arTree)
                ) {
                    $arTree[$arElement["IBLOCK_SECTION_ID"]]["E"][] = $arElement;
                    unset($arElements[$i]);
                }
            }

            foreach ($arTree as $arSection) {
                $margin = max((int)$arSection['DEPTH_LEVEL'], 1) - 1;
                $options .= '<optgroup label="' . str_repeat(' . ', $margin) . $arSection['NAME'] . '">';
                if (isset($arSection["E"])) {
                    foreach ($arSection["E"] as $arItem) {
                        $options .= '<option value="' . $arItem["ID"] . '"';
                        if (in_array($arItem["~ID"], $values)) {
                            $options .= ' selected';
                            $bWasSelect = true;
                        }
                        $options .= '>' . $arItem["NAME"] . '</option>';
                    }
                }
                $options .= '</optgroup>';
            }
            foreach ($arElements as $arItem) {
                $options .= '<option value="' . $arItem["ID"] . '"';
                if (in_array($arItem["~ID"], $values)) {
                    $options .= ' selected';
                    $bWasSelect = true;
                }
                $options .= '>' . $arItem["NAME"] . '</option>';
            }

        } else {
            foreach (SignUpForProcedure::GetElements($arProperty["LINK_IBLOCK_ID"]) as $arItem) {
                $options .= '<option value="' . $arItem["ID"] . '"';
                if (in_array($arItem["~ID"], $values)) {
                    $options .= ' selected';
                    $bWasSelect = true;
                }
                $options .= '>' . $arItem["NAME"] . '</option>';
            }
        }

        return $options;
    }

    /**
     * Returns data for smart filter.
     *
     * @param array $arProperty Property description.
     * @param array $value Current value.
     * @return false|array
     */
    public static function GetExtendedValue($arProperty, $value)
    {
        $html = self::GetPublicViewHTML($arProperty, $value, array('MODE' => 'SIMPLE_TEXT'));
        if ($html <> '') {
            $text = htmlspecialcharsback($html);
            return array(
                'VALUE' => $text,
                'UF_XML_ID' => $text,
            );
        }
        return false;
    }

    public static function GetElements($IBLOCK_ID)
    {
        static $cache = array();
        $IBLOCK_ID = intval($IBLOCK_ID);

        if (!array_key_exists($IBLOCK_ID, $cache)) {
            $cache[$IBLOCK_ID] = array();
            if ($IBLOCK_ID > 0) {
                $arSelect = array(
                    "ID",
                    "NAME",
                    "IN_SECTIONS",
                    "IBLOCK_SECTION_ID",
                );
                $arFilter = array(
                    "IBLOCK_ID" => $IBLOCK_ID,
                    //"ACTIVE" => "Y",
                    "CHECK_PERMISSIONS" => "Y",
                );
                $arOrder = array(
                    "NAME" => "ASC",
                    "ID" => "ASC",
                );
                $rsItems = \CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
                while ($arItem = $rsItems->GetNext())
                    $cache[$IBLOCK_ID][] = $arItem;
            }
        }
        return $cache[$IBLOCK_ID];
    }

    public static function GetSections($IBLOCK_ID)
    {
        static $cache = [];
        $IBLOCK_ID = (int)$IBLOCK_ID;

        if (!isset($cache[$IBLOCK_ID])) {
            $cache[$IBLOCK_ID] = [];
            if ($IBLOCK_ID > 0) {
                $arSelect = [
                    'ID',
                    'IBLOCK_ID',
                    'NAME',
                    'DEPTH_LEVEL',
                    'LEFT_MARGIN',
                ];
                $arFilter = [
                    'IBLOCK_ID' => $IBLOCK_ID,
                    //'ACTIVE' => 'Y',
                    'CHECK_PERMISSIONS' => 'Y',
                ];
                $arOrder = [
                    'LEFT_MARGIN' => 'ASC',
                ];
                $rsItems = CIBlockSection::GetList($arOrder, $arFilter, false, $arSelect);
                while ($arItem = $rsItems->GetNext()) {
                    $cache[$IBLOCK_ID][$arItem['ID']] = $arItem;
                }
                unset($arItem, $rsItems);
            }
        }

        return $cache[$IBLOCK_ID];
    }

    public static function GetUIEntityEditorProperty($settings, $value)
    {
        return [
            'type' => 'custom',
        ];
    }
}