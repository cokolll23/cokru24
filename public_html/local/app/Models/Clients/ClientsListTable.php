<?php

namespace Models\Clients;

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\ORM\Data\DataManager,
    Bitrix\Main\ORM\Fields\IntegerField,
    Bitrix\Main\ORM\Fields\StringField,
    Bitrix\Main\ORM\Fields\Validators\LengthValidator;

class ClientsListTable extends DataManager
{

    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'client_lists';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     */
    public static function getMap()
    {
        return [
            'ID' => (new IntegerField('ID',
                ['title' => 'ID',]
            ))->configureTitle(Loc::getMessage('LISTS_ENTITY_ID_FIELD'))
                ->configurePrimary(true)
                ->configureAutocomplete(true),
            'UF_NAME' => (new StringField('UF_NAME',
                [
                    'validation' => [__CLASS__, 'validateUfName']
                ]
            ))->configureTitle('Имя'),
            'UF_LASTNAME' => (new StringField('UF_LASTNAME',
                [
                    'validation' => [__CLASS__, 'validateUfLastname']
                ]
            ))->configureTitle('Фамилия'),
            'UF_PHONE' => (new StringField('UF_PHONE',
                [
                    'validation' => [__CLASS__, 'validateUfPhone']
                ]
            ))->configureTitle('Телефон'),
            'UF_JOBPOSITION' => (new StringField('UF_JOBPOSITION',
                [
                    'validation' => [__CLASS__, 'validateUfJobposition']
                ]
            ))->configureTitle('Должность'),
            'UF_SCORE' => (new StringField('UF_SCORE',
                [
                    'validation' => [__CLASS__, 'validateUfScore']
                ]
            ))->configureTitle('Лояльность клиента'),
        ];
    }

    /**
     * Returns validators for UF_NAME field.
     *
     * @return array
     */
    public static function validateUfName()
    {
        return [
            new LengthValidator(null, 50),
        ];
    }

    /**
     * Returns validators for UF_LASTNAME field.
     *
     * @return array
     */
    public static function validateUfLastname()
    {
        return [
            new LengthValidator(null, 50),
        ];
    }

    /**
     * Returns validators for UF_PHONE field.
     *
     * @return array
     */
    public static function validateUfPhone()
    {
        return [
            new LengthValidator(null, 50),
        ];
    }

    /**
     * Returns validators for UF_JOBPOSITION field.
     *
     * @return array
     */
    public static function validateUfJobposition()
    {
        return [
            new LengthValidator(null, 50),
        ];
    }

    /**
     * Returns validators for UF_SCORE field.
     *
     * @return array
     */
    public static function validateUfScore()
    {
        return [
            new LengthValidator(null, 50),
        ];
    }

}