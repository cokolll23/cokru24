<?php

namespace Lab\Garage;

use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\TextField;
use Bitrix\Main\ORM\Fields\DateField;
use Bitrix\Main\ORM\Fields\Relations\ManyToMany;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
class GarageTable extends DataManager
{
    public static function getTableName(): string
    {
        return 'garage';
    }

    public static function getMap(): array
    {
        return [
            (new IntegerField('ID'))
                ->configurePrimary()
                ->configureAutocomplete()
                ->configureTitle(Loc::getMessage('LISTS_ENTITY_ID_FIELD')),

            (new StringField('MARKA'))
                ->configureRequired()
                ->configureSize(255)
                ->configureTitle(Loc::getMessage('LISTS_ENTITY_MARKA_FIELD')),

            (new StringField('MODEL'))
                ->configureRequired()
                ->configureSize(255)
                ->configureTitle(Loc::getMessage('LISTS_ENTITY_MODEL_FIELD')),

            (new StringField('YEAR_CREATED'))
                ->configureRequired()
                ->configureSize(255)
                ->configureTitle(Loc::getMessage('LISTS_ENTITY_YEAR_CREATED_FIELD')),

            (new StringField('COLOR'))
                ->configureRequired()
                ->configureSize(255)
                ->configureTitle(Loc::getMessage('LISTS_ENTITY_COLOR_FIELD')),

            (new StringField('MILEGE'))
                ->configureRequired()
                ->configureSize(255)
                ->configureTitle(Loc::getMessage('LISTS_ENTITY_MILEGE_FIELD')),
            (new StringField('CONTACT_ID'))
                ->configureRequired()
                ->configureSize(255)
                ->configureTitle('CONTACT_ID'),


        ];
    }
}