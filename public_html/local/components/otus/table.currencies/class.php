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



class TableCurrenciesComponent extends CBitrixComponent
{

    protected $request;

    /**
     * Подготовка параметров компонента
     * @param $arParams
     * @return mixed
     */
    public function onPrepareComponentParams($arParams) {
        // тут пишем логику обработки параметров, дополнение к параметрам по умолчанию
        return $arParams;
    }


    private function getColumn()
    {
        $fieldMap = Bitrix\Currency\CurrencyTable::getMap();
        $columns = [];
        foreach ($fieldMap as $key => $field) {
            $columns[] = array(
                'id' => $field->getName(),
                'name' => $field->getTitle()
            );
        }
        return $columns;
    }



    private function getList( $limit = 1)
    {
        $list = [];
        $data = Bitrix\Currency\CurrencyTable::getList([
            'select' => ['CURRENCY', 'AMOUNT_CNT','AMOUNT','CURRENT_BASE_RATE','NUMCODE',],
            'filter' => [],
            'order' => []
        ]);

        while ($item = $data->fetch()) {
            $list[] = array('data' => $item);
        }

        return $list;
    }


    /**
     * Точка входа в компонент
     * Должна содержать только последовательность вызовов вспомогательых ф-ий и минимум логики
     * всю логику стараемся разносить по классам и методам
     */
    public function executeComponent() {

        try
        {

            $this->$request = Application::getInstance()->getContext()->getRequest();

            if(isset($this->$request['report_list'])){
                $page = explode('page-', $this->$request['report_list']);
                $page = $page[1];
            }else{
                $page = 1;
            }

            $this->arResult['SHOW_ROW_CHECKBOXES'] = false;

            if($this->arParams['SHOW_CHECKBOXES'] == 'Y'){
                $this->arResult['SHOW_ROW_CHECKBOXES'] = true;
            }

            $this->arResult['COLUMNS'] = $this->getColumn(); // получаем названия полей таблицы


            $this->arResult['NUM_PAGE'] = (empty($this->arParams['NUM_PAGE']))? 20 : $this->arParams['NUM_PAGE'];
            $this->arResult['LISTS'] = $this->getList($page, $this->arResult['NUM_PAGE']); // получаем записи таблицы
            $this->arResult['COUNT'] =  Clients::getCount(); // количество записей

            // подключаем шаблон
            $this->IncludeComponentTemplate();

        }
        catch (SystemException $e)
        {
            ShowError($e->getMessage());
        }

    }


}