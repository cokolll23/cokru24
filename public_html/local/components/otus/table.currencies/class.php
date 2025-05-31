<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

/** @global CIntranetToolbar $INTRANET_TOOLBAR */

use Bitrix\Main\Context,
    Bitrix\Main\Application,
    Bitrix\Main\Type\DateTime,
    Bitrix\Main\Loader,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main\Engine\Contract\Controllerable,
    Bitrix\Iblock;
use Bitrix\Main\Engine\Contract;
use \Models\Clients\ClientsListTable as Clients;

global $arParams;

class OtusTableCurrenciesComponent extends CBitrixComponent
{

    /**
     * Подготовка параметров компонента
     * @param $arParams
     * @return mixed
     */
    public function onPrepareComponentParams($arParams) {
        // тут пишем логику обработки параметров, дополнение к параметрам по умолчанию
        return $arParams;
    }

    private function getList()
    {


        $data = Bitrix\Currency\CurrencyTable::getList([
            'select' => ['CURRENCY', 'AMOUNT_CNT','AMOUNT','CURRENT_BASE_RATE','NUMCODE',],
            'filter' => ['CURRENCY' => $this->$arParams["CURRENCIES"]]
        ])->fetchAll();

       /* while ($item = $data->fetch()) {
            $list[] = array('data' => $item);
        }*/

        return $data;
    }


    /**
     * Точка входа в компонент
     * Должна содержать только последовательность вызовов вспомогательых ф-ий и минимум логики
     * всю логику стараемся разносить по классам и методам
     */
    public function executeComponent() {

        try
        {
           // echo $this->$arParams["CURRENCIES_LIST_TITLE"];
           // die();

            $this->arResult['LIST'] = $this->getList();
            $this->arResult['LISTs'] = $this->$arParams["CURRENCIES"];

            // подключаем шаблон
            $this->IncludeComponentTemplate();

        }
        catch (SystemException $e)
        {
            ShowError($e->getMessage());
        }

    }


}