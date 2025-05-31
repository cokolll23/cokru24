<?php
namespace Lab\Crmcustomtab\Orm;

use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\TextField;
use Bitrix\Main\ORM\Fields\DateField;
use Bitrix\Main\ORM\Fields\Relations\ManyToMany;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class BookTable extends DataManager
{
    public static function getTableName(): string
    {
        return 'lab_book';
    }

    public static function getMap(): array
    {
        return [
            (new IntegerField('ID'))
                ->configurePrimary()
                ->configureAutocomplete()
                ->configureTitle(Loc::getMessage('LAB_CRMCUSTOMTAB_BOOK_TABLE_ID')),

            (new StringField('TITLE'))
                ->configureRequired()
                ->configureSize(255)
                ->configureTitle(Loc::getMessage('LAB_CRMCUSTOMTAB_BOOK_TABLE_TITLE')),

            (new IntegerField('YEAR'))
                ->configureTitle(Loc::getMessage('LAB_CRMCUSTOMTAB_BOOK_TABLE_YEAR')),

            (new IntegerField('PAGES'))
                ->configureTitle(Loc::getMessage('LAB_CRMCUSTOMTAB_BOOK_TABLE_PAGES')),

            (new TextField('DESCRIPTION'))
                ->configureTitle(Loc::getMessage('LAB_CRMCUSTOMTAB_BOOK_TABLE_DESCRIPTION')),

            (new DateField('PUBLISH_DATE'))
                ->configureTitle(Loc::getMessage('LAB_CRMCUSTOMTAB_BOOK_TABLE_PUBLISH_DATE')),

            (new ManyToMany('AUTHORS', AuthorTable::class))
                ->configureTableName('lab_book_author')
                ->configureLocalPrimary('ID', 'BOOK_ID')
                ->configureRemotePrimary('ID', 'AUTHOR_ID')
                ->configureTitle(Loc::getMessage('LAB_CRMCUSTOMTAB_BOOK_TABLE_AUTHORS'))
        ];
    }
}
