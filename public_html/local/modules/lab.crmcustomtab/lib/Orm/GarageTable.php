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
                ->configureTitle('ID'),

            (new StringField('MARKA'))
                ->configureRequired()
                ->configureSize(255)
                ->configureTitle(' Марка авто'),

            (new StringField('MODEL'))
                ->configureRequired()
                ->configureSize(255)
                ->configureTitle(' Модель '),

            (new StringField('YEAR'))
                ->configureRequired()
                ->configureSize(255)
                ->configureTitle(' Дата выпуска'),

            (new StringField('COLOR'))
                ->configureRequired()
                ->configureSize(255)
                ->configureTitle(' Цвет кузова'),

            (new StringField('MILEAGE'))
                ->configureRequired()
                ->configureSize(255)
                ->configureTitle(' Пробег '),
            (new StringField('CONTACT_ID'))
                ->configureRequired()
                ->configureSize(255)
                ->configureTitle('CONTACT_ID'),
            (new StringField('UF_CRM_DEAL_VIN'))
                ->configureRequired()
                ->configureSize(255)
                ->configureTitle('CONTACT_ID'),


        ];
    }
}