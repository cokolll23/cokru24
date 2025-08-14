<?php

use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UI\PageNavigation;
use Bitrix\Main\Grid\Options as GridOptions;
use Bitrix\Main\UI\Filter\Options as FilterOptions;
use Lab\Crmcustomtab\Orm\BookTable;
use Lab\Crmcustomtab\Orm\GarageTable;
use Bitrix\Main\Loader;
use Bitrix\Main\ORM\Query\Result;

Loader::includeModule('lab.crmcustomtab');
class BookGrid extends \CBitrixComponent implements Controllerable
{
    public function configureActions(): array
    {
        return [];
    }

    private function getElementActions(): array
    {
        return [];
    }

    private function getHeaders(): array
    {
        return [
            [
                'id' => 'ID',
                'name' => 'ID',
                'sort' => 'ID',
                'default' => true,
            ],
            [
                'id' => 'MARKA',
                'name' => Loc::getMessage('BOOK_GRID_BOOK_TITLE_LABEL'),
                'sort' => 'MARKA',
                'default' => true,
            ],
            [
                'id' => 'MODEL',
                'name' => Loc::getMessage('BOOK_GRID_BOOK_MODEL_LABEL'),
                'sort' => 'MODEL',
                'default' => true,
            ],
            [
                'id' => 'YEAR',
                'name' => Loc::getMessage('BOOK_GRID_BOOK_PUBLISHING_YEAR_LABEL'),
                'sort' => 'YEAR',
                'default' => true,
            ],
            [
                'id' => 'COLOR',
                'name' => Loc::getMessage('BOOK_GRID_BOOK_COLOR_LABEL'),
                'sort' => 'COLOR',
                'default' => true,
            ],
            [
                'id' => 'MILEAGE',
                'name' => Loc::getMessage('BOOK_GRID_BOOK_MILEAGE_LABEL'),
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
        $this->arResult['FILTER_ID'] = 'BOOK_GRID';

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

        $bookIdsQuery = GarageTable::query()
            ->setSelect(['ID'])
            ->setFilter($filter)
            ->setLimit($nav->getLimit())
            ->setOffset($nav->getOffset())
            ->setOrder($sort['sort'])
        ;

        $countQuery = GarageTable::query()
            ->setSelect(['ID'])
            ->setFilter($filter)
        ;
        $nav->setRecordCount($countQuery->queryCountTotal());

        $bookIds = array_column($bookIdsQuery->exec()->fetchAll(), 'ID');

        if (!empty($bookIds)) {
            $books = GarageTable::getList([
                'filter' => ['ID' => $bookIds,'CONTACT_ID'=> $this ->arParams['DEAL_ID']] + $filter,
                'select' => [
                    '*',
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
                    'MARKA' => $book['MARKA'],
                    'MODEL' => $book['MODEL'],
                    'YEAR' => $book['YEAR'],
                    'COLOR' => $book['COLOR'],
                    'MILEAGE' => $book['MILEAGE'],
                    //'AUTHORS' => []
                ];
            }


        }

        foreach ($groupedBooks as $book) {

            $gridList[] = [
                'data' => [
                    'ID' => $book['ID'],
                    'MARKA' => $book['MARKA'],
                    'MODEL' => $book['MODEL'],
                    'YEAR' => $book['YEAR'],
                    'COLOR' => $book['COLOR'],
                    'MILEAGE' => $book['MILEAGE'],
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
