<?php

namespace Models\Books;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\DateField;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\TextField;
use Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Bitrix\Main\ORM\Fields\Relations\ManyToMany;
use Models\Books\AuthorTable;

/**
 * Class Table
 *
 * Fields:
 * <ul>
 * <li> id int mandatory
 * <li> name string(50) optional
 * <li> text text optional
 * <li> publish_date date optional
 * <li> ISBN string(50) optional
 * <li> author_id int optional
 * <li> publisher_id int optional
 * <li> wikiprofile_id int optional
 * </ul>
 *
 * @package Bitrix\
 **/

class BooksTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'books';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     */
    public static function getMap()
    {
        return [
            new IntegerField(
                'id',
                [
                    'primary' => true,
                    'autocomplete' => true,
                    'title' => 'ID',
                ]
            ),
            new StringField(
                'name',
                [
                    'validation' => function()
                    {
                        return[
                            new LengthValidator(null, 50),
                        ];
                    },
                    'title' => 'NAME',
                ]
            ),
            new TextField(
                'text',
                [
                    'title' => 'TEXT_FIELD',
                ]
            ),
            new DateField(
                'publish_date',
                [
                    'title' => 'PUBLISH_DATE',
                ]
            ),
            new StringField(
                'ISBN',
                [
                    'validation' => function()
                    {
                        return[
                            new LengthValidator(null, 50),
                        ];
                    },
                    'title' =>'ISBN',
                ]
            ),
            new IntegerField(
                'author_id',
                [
                    'title' => 'AUTHOR_ID',
                    'size' => 1,
                ]
            ),
            new IntegerField(
                'publisher_id',
                [
                    'title' => 'PUBLISHER_ID',
                ]
            ),
            new IntegerField(
                'wikiprofile_id',
                [
                    'title' => 'WIKIPROFILE_ID',
                ]
            ),

            (new ManyToMany('AUTHORS', AuthorTable::class))
                ->configureTableName('book_author')
                ->configureLocalPrimary('ID', 'BOOK_ID')
                ->configureRemotePrimary('ID', 'AUTHOR_ID')
                ->configureTitle('AUTHORS')
        ];
    }
}