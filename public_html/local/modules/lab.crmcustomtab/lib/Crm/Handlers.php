<?php
namespace Lab\Crmcustomtab\Crm;

use Lab\Crmcustomtab\Orm\BookTable;
use Bitrix\Main\Event;
use Bitrix\Main\EventResult;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
class Handlers
{
    public static function updateTabs(Event $event): EventResult
    {
        $entityTypeId = $event->getParameter('entityTypeID');
        $entityId = $event->getParameter('entityID');
        $tabs = $event->getParameter('tabs');
        if($entityTypeId == \CCrmOwnerType::Contact) {// то что это Contact сущность CRM
            $tabs[] = [ // изменение массива tabs формирующего вкладки
                'id' => 'book_tab_' . $entityTypeId . '_' . $entityId,
                'name' => Loc::getMessage('LAB_CRMCUSTOMTAB_TAB_TITLE'),
                'enabled' => true,
                // 'html'=> '',
                'loader' => [
                    'serviceUrl' => sprintf(// /bitrix/components/lab.crmcustomtab/book.grid компонент с bitrix:main.ui.grid
                        '/bitrix/components/lab.crmcustomtab/book.grid/lazyload.ajax.php?site=%s&%s',
                        \SITE_ID,
                        \bitrix_sessid_get(),
                    ),
                    'componentData' => [
                        'template' => '',
                        'params' => [
                            'ORM' => GarageTable::class,// /local/modules/lab.crmcustomtab/lib/Orm/GarageTable.php
                            'DEAL_ID' => $entityId,
                        ],
                    ],
                ],
            ];
        }
        return new EventResult(EventResult::SUCCESS, ['tabs' => $tabs,]);
    }

}
