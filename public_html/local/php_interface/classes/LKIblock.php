<?php
use Bitrix\Main\
{Application, Data\Cache, Loader};
use Bitrix\Sale;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;


/**
 * Работаем с IBlock Bitrix
 */
CModule::IncludeModule('highloadblock');

class LKIblock {

     /**
     * Все данные из ИБ с фильтром сохраняемые в кеш
     * @param $iBlockCode
     * @param array $userFilter
     * @return mixed
     */
    
    public static function getIblockAllFieldsWithFilterTagged($iBlockCode,$userFilter=array()){
        
        $return=array();

        $cache = Cache::createInstance();
        $taggedCache = Application::getInstance()->getTaggedCache();
        $f = __FUNCTION__;
        $cacheTime = 60 * 60 * 24 * 365;
        $cacheId = md5( serialize( array( $f,$iBlockCode, $userFilter)));
        $cacheDir = '/bx24/iblocks/' . $f . '/'.$iBlockCode.'/';
        if( $cache->initCache( $cacheTime, $cacheId, $cacheDir ) )
            $return = $cache->getVars();
        elseif( $cache->startDataCache() ) {

            $taggedCache->startTagCache($cacheDir);
             // Получаем ID информационного блока по его символьному коду
            $IBlockID = self::getIdbyCode($iBlockCode);

            // Задаем список полей, которые хотим получить
            $arSelect = array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PREVIEW_TEXT", "PREVIEW_PICTURE", 'ACTIVE', "DATE_ACTIVE_TO");
            
            // Определяем фильтр для выборки элементов информационного блока
            $arFilter = array("IBLOCK_ID" => $IBlockID);

            // Объединяем пользовательский фильтр с основным фильтром
            $arFilter = array_merge($arFilter, $userFilter);
            
            // Получаем список элементов информационного блока с заданным фильтром
            $res = CIBlockElement::GetList(array('SORT' => 'ASC'), $arFilter, false, false, $arSelect);
            
            // Цикл по результатам выборки
            while ($ob = $res->GetNextElement()) {
                // Получаем поля элемента
                $arFields = $ob->GetFields();
                $ret = $arFields;

                // Получаем свойства элемента
                $arProps = $ob->GetProperties();
                $ret['PROPS'] = $arProps;

                // Формируем массив с полями и свойствами элемента
                $return[$arFields['ID']] = $ret;

                // Очищаем временную переменную
                unset($ret);
            }
            if( empty( $return ) )
            {
                $taggedCache->abortTagCache();
                $cache->abortDataCache();
                return $return;
            }
            $taggedCache->registerTag('iblock_id_'.$IBlockID);
            $taggedCache->endTagCache();
            $cache->endDataCache( $return );
        }

        // Возвращаем полученные данные
        return $return;
    }


    /**
     * Все данные из ИБ с фильтром
     * @TODO: Прикрутить кеш
     * @param $iBlockCode
     * @param array $userFilter
     * @return mixed
     */
    public static function getIblockAllFieldsWithFilter($iBlockCode, $userFilter = array(), $ElementID = false){
        // Получаем ID информационного блока по его символьному коду
        $IBlockID = self::getIdbyCode($iBlockCode);

        // Задаем список полей, которые хотим получить
        $arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PREVIEW_TEXT", "PREVIEW_PICTURE", "ACTIVE", "DATE_ACTIVE_TO");

        // Определяем фильтр для выборки элементов информационного блока
        $arFilter = Array("IBLOCK_ID" => $IBlockID);
        
        // Получаем id записи для фильтрации
        if($ElementID){ 
            $arFilter["ID"] = $ElementID;
        }

        // Объединяем пользовательский фильтр с основным фильтром
        $arFilter = array_merge($arFilter, $userFilter);

        // Получаем список элементов информационного блока с заданным фильтром
        $res = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter, false, false, $arSelect);

        // Цикл по результатам выборки
        while($ob = $res->GetNextElement()){
            // Получаем поля элемента
            $arFields = $ob->GetFields();
            $ret = $arFields;

            // Получаем свойства элемента
            $arProps = $ob->GetProperties();
            $ret['PROPS'] = $arProps;

            // Формируем массив с полями и свойствами элемента
            $return[$arFields['ID']] = $ret;

            // Очищаем временную переменную
            unset($ret);
        }

        // Возвращаем полученные данные
        return $return;
    }

    /**
     * Получает ID инфоблока по коду ИБ
     * @param string $code
     * @return ID
     */
    public static function getIdbyCode(string $code)
    {
        Loader::includeModule( 'iblock' );
        $res = CIBlock::GetList(
            array(),
            array(
                "CODE" => $code,
                'CHECK_PERMISSIONS' => 'N'
            ),
            false
        );
        $ID=false;
        while ($ar_res = $res->Fetch()) {
            $ID = $ar_res['ID'];
        }
        return $ID;
    }


}