<?php

namespace Models\HospitalClientsTable;

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\ORM\Data\DataManager,
    Bitrix\Main\ORM\Fields\IntegerField,
    Bitrix\Main\ORM\Fields\StringField,
    Bitrix\Main\ORM\Fields\Validators\LengthValidator,
    Bitrix\Main\ORM\Fields\Validators\RegExpValidator,
    Bitrix\Main\ORM\Fields\Relations\Reference,
    Bitrix\Main\ORM\Fields\Relations\OneToMany,
    Bitrix\Main\Entity\Query\Join;

/**
 * Class HospitalClientsTable
 *
 * @package Models
 */
class HospitalClientsTable extends DataManager
{

    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'hospital_clients';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     */
    public static function getMap()
    {
        return [
            'id' => (new IntegerField('id',
                []
            ))->configureTitle(Loc::getMessage('CLIENTS_ENTITY_ID_FIELD'))
                ->configurePrimary(true)
                ->configureAutocomplete(true),
            'first_name' => (new StringField('first_name',
                [
                    'validation' => [__CLASS__, 'validateFirstName']
                ]
            ))->configureTitle(Loc::getMessage('CLIENTS_ENTITY_FIRST_NAME_FIELD')),
            'last_name' => (new StringField('last_name',
                [
                    'validation' => [__CLASS__, 'validateLastName']
                ]
            ))->configureTitle(Loc::getMessage('CLIENTS_ENTITY_LAST_NAME_FIELD')),

            'age' => (new IntegerField(
                'age',
                [
                    'validation' => [__CLASS__, 'validateAge']
                ]
            ))->configureTitle(Loc::getMessage('CLIENTS_ENTITY_AGE_FIELD')),

            'doctor_id' => (new IntegerField('doctor_id',
                []
            ))->configureTitle(Loc::getMessage('CLIENTS_ENTITY_DOCTOR_ID_FIELD')),
            'procedure_id' => (new IntegerField('procedure_id',
                []
            ))->configureTitle(Loc::getMessage('CLIENTS_ENTITY_PROCEDURE_ID_FIELD')),
            'contact_id' => (new IntegerField('contact_id',
                []
            ))->configureTitle(Loc::getMessage('CLIENTS_ENTITY_CONTACT_ID_FIELD')),

            // виртуальное поле CONTACT которое получает запись из таблицы контактов
            // где ID равен значению contact_id таблицы hospital_clients
            (new Reference(
                'CONTACT',
                \Bitrix\CRM\ContactTable::class,
                Join::on('this.contact_id', 'ref.ID'))
            )->configureJoinType('inner'),

            (new Reference('DOCTOR', \Bitrix\Iblock\Elements\ElementDoctorsTable::class,
                Join::on('this.doctor_id', 'ref.ID')))
                ->configureJoinType('inner'),

            (new Reference('PROCEDURE', \Bitrix\Iblock\Elements\ElementProceduresTable::class,
                Join::on('this.procedure_id', 'ref.ID')))
                ->configureJoinType('inner'),
        ];
    }

    /**
     * Returns validators for first_name field.
     *
     * @return array
     */
    public static function validateFirstName()
    {
        return [
            new LengthValidator(null, 50),
            // new RegExpValidator("/^([а-яё]+|[a-z]+)$/i")
        ];
    }

    /**
     * Returns validators for last_name field.
     *
     * @return array
     */
    public static function validateLastName()
    {
        return [
            new LengthValidator(null, 50),
            // new RegExpValidator("/^([а-яё]+|[a-z]+)$/i")
        ];
    }


    /**
     * Returns validators for age field.
     *
     * @return array
     */

    public static function validateAge(){
        return [
            new LengthValidator(null, null),
        ];
    }





}
