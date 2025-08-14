<?php
// todo перенести компонент в модуль
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UI\PageNavigation;
use Bitrix\Main\Grid\Options as GridOptions;
use Bitrix\Main\UI\Filter\Options as FilterOptions;
use Bitrix\Main\Loader;
use Bitrix\Main\ORM\Query\Result;
use Bitrix\Crm\DealTable;

Loader::includeModule('lab.crmcustomtab');

class DealsGrid extends \CBitrixComponent implements Controllerable
{
    // обязательный метод предпроверки данных
    public function configureActions()
    {
        // устанавливаем фильтры (Bitrix\Main\Engine\ActionFilter\Authentication() и Bitrix\Main\Engine\ActionFilter\HttpMethod() и Bitrix\Main\Engine\ActionFilter\Csrf())
        return [
            'test' => [
                'prefilters' => [
                    new Bitrix\Main\Engine\ActionFilter\Authentication(),
                    new Bitrix\Main\Engine\ActionFilter\HttpMethod(array(Bitrix\Main\Engine\ActionFilter\HttpMethod::METHOD_GET, Bitrix\Main\Engine\ActionFilter\HttpMethod::METHOD_POST)),
                    new Bitrix\Main\Engine\ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ]
        ];
    }

    // основной метод исполнитель, сюда передаются параметры из ajax запроса, навания точно такие же как и при отправке запроса, $_REQUEST['param1'] будет передан в $param1
    public function testAction($param2 = 'qwe', $param1 = '')
    {
        return [
            'asd' => $param1,
            'count' => 200
        ];
    }

    private function getElementActions(): array
    {
        return [];
    }

    private function getHeaders(): array
    {
        return [
            [
                'id' => 'DATE_CREATE',
                'name' => Loc::getMessage('DEALS_GRID_DATE_CREATE_LABEL'),
                'sort' => 'DATE_CREATE',
                'default' => true,
            ],
            [
                'id' => 'ID',
                'name' => 'ID',
                'sort' => 'ID',
                'default' => true,
            ],
            [
                'id' => 'TITLE',
                'name' => Loc::getMessage('DEALS_GRID_TITLE_LABEL'),
                'sort' => 'TITLE',
                'default' => true,
            ],
            [
                'id' => 'MODEL',
                'name' => Loc::getMessage('DEALS_GRID_MODEL_LABEL'),
                'sort' => 'MODEL',
                'default' => true,
            ],
            [
                'id' => 'VIN',
                'name' => Loc::getMessage('DEALS_GRID_VIN_LABEL'),
                'sort' => 'VIN',
                'default' => true,
            ],


            [
                'id' => 'YEAR',
                'name' => Loc::getMessage('DEALS_GRID_YEAR_LABEL'),
                'sort' => 'YEAR',
                'default' => true,
            ],
            [
                'id' => 'MILEAGE',
                'name' => Loc::getMessage('DEALS_GRID_MILEAGE_LABEL'),
                'sort' => 'MILEAGE',
                'default' => true,
            ],
        ];
    }

    public function executeComponent(): void
    {
        $this->prepareGridData();
        $this->includeComponentTemplate();
    }

    private function prepareGridData(): void
    {
        $this->arResult['HEADERS'] = $this->getHeaders();
        $this->arResult['FILTER_ID'] = 'DEALS_GRID';

        $gridOptions = new GridOptions($this->arResult['FILTER_ID']);
        $navParams = $gridOptions->getNavParams();

        $nav = new PageNavigation($this->arResult['FILTER_ID']);
        $nav->allowAllRecords(true)
            ->setPageSize($navParams['nPageSize'])
            ->initFromUri();

        $filterOption = new FilterOptions($this->arResult['FILTER_ID']);
        $filterData = $filterOption->getFilter([]);
        $filter = $this->prepareFilter($filterData);


        $sort = $gridOptions->getSorting([
            'sort' => [
                'ID' => 'DESC',
            ],
            'vars' => [
                'by' => 'by',
                'order' => 'order',
            ],
        ]);

        $bookIdsQuery = DealTable::query()
            ->setSelect(['ID'])
            ->setFilter($filter)
            ->setLimit($nav->getLimit())
            ->setOffset($nav->getOffset())
            ->setOrder($sort['sort']);

        $countQuery = DealTable::query()
            ->setSelect(['ID'])
            ->setFilter($filter);
        $nav->setRecordCount($countQuery->queryCountTotal());

        $bookIds = array_column($bookIdsQuery->exec()->fetchAll(), 'ID');


        if (!empty($bookIds)) {

            $dealByGarageTableId = DealTable::getList([
                'filter' => ['=UF_CRM_DEAL_GARAGE_TABLE_ITEM_ID' => $this->arParams['id']],
                'select' => [
                    'ID',
                    'UF_CRM_DEAL_VIN',
                ],
                'order' => [],
            ]);
            if ($dealUF_CRM_DEAL_VIN = $dealByGarageTableId->fetch()) {
                $UF_CRM_DEAL_VIN =$dealUF_CRM_DEAL_VIN['UF_CRM_DEAL_VIN'];

            }

            $books = DealTable::getList([
                'filter' => ['ID' => $bookIds, '=UF_CRM_DEAL_VIN' => $UF_CRM_DEAL_VIN] + $filter,
                'select' => [
                    'ID',
                    'TITLE',
                    'DATE_CREATE',
                    'UF_CRM_DEAL_MODEL',
                    'UF_CRM_DEAL_YEAR',
                    'UF_CRM_DEAL_COLOR',
                    'UF_CRM_DEAL_MILEAGE',
                    'UF_CRM_DEAL_VIN',
                ],
                'order' => $sort['sort'],
            ]);

            $this->arResult['GRID_LIST'] = $this->prepareGridList($books);
        } else {
            $this->arResult['GRID_LIST'] = [];
        }

        $this->arResult['NAV'] = $nav;
        $this->arResult['UI_FILTER'] = $this->getFilterFields();
    }

    private function prepareFilter(array $filterData): array
    {
        $filter = [];

        if (!empty($filterData['FIND'])) {
            $filter['%TITLE'] = $filterData['FIND'];
        }

        if (!empty($filterData['TITLE'])) {
            $filter['%TITLE'] = $filterData['TITLE'];
        }

        if (!empty($filterData['YEAR_from'])) {
            $filter['>=YEAR'] = $filterData['YEAR_from'];
        }

        if (!empty($filterData['YEAR_to'])) {
            $filter['<=YEAR'] = $filterData['YEAR_to'];
        }

        if (!empty($filterData['PUBLISH_DATE_from'])) {
            $filter['>=PUBLISH_DATE'] = $filterData['PUBLISH_DATE_from'];
        }

        if (!empty($filterData['PUBLISH_DATE_to'])) {
            $filter['<=PUBLISH_DATE'] = $filterData['PUBLISH_DATE_to'];
        }

        return $filter;
    }

    private function prepareGridList(Result $books): array
    {
        $gridList = [];
        $groupedBooks = [];

        while ($book = $books->fetch()) {
            $bookId = $book['ID'];

            if (!isset($groupedBooks[$bookId])) {

                $groupedBooks[$bookId] = [
                    'ID' => $book['ID'],
                    'TITLE' => $book['TITLE'],
                    'DATE_CREATE' => $book['DATE_CREATE'],
                    'MODEL' => $book['UF_CRM_DEAL_MODEL'],
                    'YEAR' => $book['UF_CRM_DEAL_YEAR'],
                    'COLOR' => $book['UF_CRM_DEAL_COLOR'],
                    'MILEAGE' => $book['UF_CRM_DEAL_MILEAGE'],
                    'VIN' => $book['UF_CRM_DEAL_VIN'],
                    //'AUTHORS' => []
                ];
            }


        }

        foreach ($groupedBooks as $book) {

            $gridList[] = [
                'data' => [
                    'ID' => $book['ID'],
                    'TITLE' => $book['TITLE'],
                    'DATE_CREATE' => $book['DATE_CREATE'],
                    'MODEL' => $book['MODEL'],
                    'YEAR' => $book['YEAR'],
                    'COLOR' => $book['COLOR'],
                    'MILEAGE' => $book['MILEAGE'],
                    'VIN' => $book['VIN'],
                    /* 'AUTHORS' => implode(', ', $book['AUTHORS']),
                     'PUBLISH_DATE' => $book['PUBLISH_DATE']->format('d.m.Y'),*/
                ],
                'actions' => $this->getElementActions(),
            ];
        }

        return $gridList;
    }

    private function getFilterFields(): array
    {
        return [
            [
                'id' => 'TITLE',
                'name' => Loc::getMessage('BOOK_GRID_BOOK_TITLE_LABEL'),
                'type' => 'string',
                'default' => true,
            ],
            [
                'id' => 'MODEL',
                'name' => Loc::getMessage('BOOK_GRID_BOOK_MODEL_LABEL'),
                'type' => 'string',
                'default' => true,
            ],
            [
                'id' => 'YEAR',
                'name' => Loc::getMessage('BOOK_GRID_BOOK_PUBLISHING_YEAR_LABEL'),
                'type' => 'string',
                'default' => true,
            ],
            [
                'id' => 'PUBLISH_DATE',
                'name' => Loc::getMessage('BOOK_GRID_BOOK_PUBLISHING_DATE_LABEL'),
                'type' => 'date',
                'default' => true,
            ],
        ];
    }
}
