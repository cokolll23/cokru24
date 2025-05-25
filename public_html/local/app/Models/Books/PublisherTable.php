<?php
namespace Models\Books;
use Bitrix\Main\Localization\Loc,
    Bitrix\Main\ORM\Data\DataManager,
    Bitrix\Main\ORM\Fields\IntegerField,
    Bitrix\Main\ORM\Fields\StringField,
    Bitrix\Main\ORM\Fields\Validators\LengthValidator,
    Bitrix\Main\ORM\Fields\Relations\Reference,
    Bitrix\Main\ORM\Fields\Relations\OneToMany,
    Bitrix\Main\ORM\Fields\Relations\ManyToMany,
    Bitrix\Main\Entity\Query\Join;

use Models\Books\BooksTable as Books;
/**
 * Class PublisherTable
 *
 * @package Models
 **/

class PublisherTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'publishers';
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
            ))->configureTitle(Loc::getMessage('_ENTITY_ID_FIELD'))
                ->configurePrimary(true)
                ->configureAutocomplete(true),
            'name' => (new StringField('name',
                [
                    'validation' => [__CLASS__, 'validateName']
                ]
            ))->configureTitle(Loc::getMessage('_ENTITY_NAME_FIELD')),

            // (new OneToMany('BOOKS', Books::class, 'publisher_id'))
            // ->configureJoinType('inner')

            (new ManyToMany('BOOKS', Books::class))
                ->configureTableName('book_publisher')
                ->configureRemoteReference('BOOKS')
                ->configureLocalPrimary('id', 'book_id')
                ->configureLocalReference('PUBLISHERS')
                ->configureRemotePrimary('id', 'publisher_id'),


        ];
    }

    /**
     * Returns validators for name field.
     *
     * @return array
     */
    public static function validateName()
    {
        return [
            new LengthValidator(null, 50),
        ];
    }
}