<?
use Bitrix\Main\
{Loader, Application, Data\Cache, EventManager};


class LKAgent
{

    /*
     * Агент
     * чистил заполненные за день данные
     */
    public static function clearOffice() {
        ini_set('memory_limit', '1024M');
        set_time_limit(10 * 60);
        //получаем все активные акции
        $officeList=LKIBlock::getIblockAllFieldsWithFilter('OFFICES');
        if ($officeList) {
            foreach ($officeList as $item) {
                $arr=array(
                    'SOTRUDNIK_NA_SMENE'=>'',
                    'STATUS_SOTRUDNIKA'=>LKIBlock::getEnumIDbyName('Рабочий день не начат','OFFICES','STATUS_SOTRUDNIKA'),
                    'PERERYVY'=>'',
                    'VREMYA_NACHALA_RABOCHEGO_DNYA'=>'',
                    'VREMYA_OKONCHANIYA_RABOCHEGO_DNYA'=>'',
                );
                LKIBlock::updateProp($item["ID"], $arr);
            }
        }
        return "LKAgent::clearOffice();";
    }

}
