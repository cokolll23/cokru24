<?
use Bitrix\Main\
{Application, Data\Cache, Loader};
use Bitrix\Sale;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;
/**
 * Хелперы для работы
 */

if (empty($_SERVER["DOCUMENT_ROOT"])) {//DOCUMENT_ROOT может быть не определён, поэтому определим его сами
    $_SERVER["DOCUMENT_ROOT"] = '/home/bitrix/ext_www/bitrixsupport.online';
}
require $_SERVER['DOCUMENT_ROOT'].'/local/vendor/autoload.php';
class LKHelper {

    /**
     * Вовзращает массив пользователя
     * @param $userID
     * @return array|bool
     */
    public static function getUserArray($userID){
        $rsUser = \CUser::GetByID($userID);
        return $rsUser->Fetch();
    }

    /**
     * Возвращает пользователей с заполнеными B24ID
     */
    public static function getAllUsersWithB24(){
        $order = array('sort' => 'asc');
        $tmp = 'sort'; // параметр проигнорируется методом, но обязан быть
        $data = \CUser::GetList($order, $tmp,
            array(
                '!UF_B24_ID' => '',
                'ACTIVE' => 'Y'
            ),
            $userParams = array(
                'SELECT' => array('UF_*'),
            )
        );

        while($arUser = $data->Fetch()) {
            $ret[]=$arUser;
        }
        return $ret;
    }

    public static function getUserByB24ID($b24ID){
        $order = array('sort' => 'asc');
        $tmp = 'sort'; // параметр проигнорируется методом, но обязан быть
        $data = \CUser::GetList($order, $tmp,
            array(
                'UF_B24_ID' => $b24ID
            ),
            $userParams = array(
                'SELECT' => array('UF_*'),
            )
        );

        return $arUser = $data->Fetch();
    }

    public static function replaceBBcodes($text)
    {
        $bbcode = new ChrisKonnertz\BBCode\BBCode();
        $rendered = $bbcode->render($text);
        return $rendered;
    }

    public static function remove_bbcode($string)
    {
        $pattern = '|[[\/\!]*?[^\[\]]*?]|si';
        $replace = '';
        return preg_replace($pattern, $replace, $string);
    }

    /**
     * Получаем дату из БД, отдает как нам нужно
     * @param $data
     * @param bool $showTime
     */
    public static function showDateTime($data,$showTime=false){
        $dateCreate=strtotime($data);
        if($showTime){
            return date('d.m.Y H:i',$dateCreate);
        }
        else{
           return date('d.m.Y',$dateCreate);
        }
    }


    public static function onlyNumbers($string){
        return preg_replace('/[^0-9]/', '', $string);;
    }

    public static function addLog($message,$itemID='',$moduleID='',$type='',$severity=''){

        CEventLog::Add(array(
            "SEVERITY" => $severity,
            "AUDIT_TYPE_ID" => $type,
            "MODULE_ID" => $moduleID,
            "ITEM_ID" => $itemID,
            "DESCRIPTION" => $message,
        ));

    }


}
