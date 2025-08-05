<?php

use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UI\PageNavigation;
use Bitrix\Main\Grid\Options as GridOptions;
use Bitrix\Main\UI\Filter\Options as FilterOptions;
use Lab\Crmcustomtab\Orm\GarageTable;
use Bitrix\Main\Loader;
use Bitrix\Main\ORM\Query\Result;

Loader::includeModule('lab.crmcustomtabgarage');
class GarageGrid extends \CBitrixComponent implements Controllerable
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
                'name' => Loc::getMessage('GARAGE_GRID_GARAGEAUTO_MARKA_LABEL'),
                'sort' => 'MARKA',
                'default' => true,
            ],
            [
                'id' => 'MODEL',
                'name' => Loc::getMessage('GARAGE_GRID_GARAGEAUTO_MODEL_LABEL'),
                'sort' => 'MODEL',
                'default' => true,
            ],
            [
                'id' => 'YEAR',
                'name' => Loc::getMessage('GARAGE_GRID_GARAGEAUTO_YEAR_LABEL'),
                'sort' => 'YEAR',
                'default' => true,
            ],
            [
                'id' => 'COLOR',
                'name' => Loc::getMessage('GARAGE_GRID_GARAGEAUTO_COLOR_LABEL'),
                'default' => true,
            ],
            [
                'id' => 'MILEGE',
                'name' => Loc::getMessage('GARAGE_GRID_GARAGEAUTO_MILEGE_LABEL'),
                'sort' => 'PUBLISH_DATE',
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
        $this->arResult['FILTER_ID'] = 'GARAGE_GRID';

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

        $autoIdsQuery = GarageTable::query()
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

        $autoIds = array_column($autoIdsQuery->exec()->fetchAll(), 'ID');

        if (!empty($autoIds)) {
            $autos = GarageTable::getList([
                'filter' => ['ID' => $autoIds] + $filter,
                'select' => [
                    'ID',
                    'MARKA',
                    'MODEL',
                    'YEAR',
                    'COLOR',
                    'MILEGE',
                ],
                'order' => $sort['sort'],
            ]);

            $this->arResult['GRID_LIST'] = $this->prepareGridList($autos);
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
            $filter['%MARKA'] = $filterData['FIND'];
        }

        if (!empty($filterData['MARKA'])) {
            $filter['%MARKA'] = $filterData['MARKA'];
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

    private function prepareGridList(Result $autos): array
    {
        $gridList = [];
        $groupedGarages = [];

        while ($auto = $autos->fetch()) {
            $autoId = $auto['ID'];

            if (!isset($groupedGarages[$autoId])) {
                $groupedGarages[$autoId] = [
                    'ID' => $auto['ID'],
                    'MARKA' => $auto['MARKA'],
                    'MODEL' => $auto['MODEL'],
                    'YEAR' => $auto['YEAR'],
                    'COLOR' => $auto['COLOR'],
                    'MILEGE' => $auto['MILEGE'],
                ];
            }

            /*if ($auto['AUTHOR_ID']) {
                $groupedGarages[$autoId]['AUTHORS'][] = implode(' ', array_filter([
                    $auto['AUTHOR_LAST_NAME'],
                    $auto['AUTHOR_FIRST_NAME'],
                    $auto['AUTHOR_SECOND_NAME']
                ]));
            }*/
        }

        foreach ($groupedGarages as $auto) {
            $gridList[] = [
                'data' => [
                    'ID' => $auto['ID'],
                    'MARKA' => $auto['MARKA'],
                    'MODEL' => $auto['MODEL'],
                    'YEAR' => $auto['YEAR'],
                    'COLOR' => $auto['COLOR'],
                    'MILEGE' => $auto['MILEGE'],
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
                'id' => 'MARKA',
                'name' => Loc::getMessage('GARAGE_GRID_GARAGEAUTO_MARKA_LABEL'),
                'type' => 'string',
                'default' => true,
            ],
            [
                'id' => 'MODEL',
                'name' => Loc::getMessage('GARAGE_GRID_GARAGEAUTO_MODEL_LABEL'),
                'type' => 'number',
                'default' => true,
            ],
            [
                'id' => 'PUBLISH_DATE',
                'name' => Loc::getMessage('GARAGE_GRID_GARAGEAUTO_MILEGE_LABEL'),
                'type' => 'date',
                'default' => true,
            ],
        ];
    }
}
