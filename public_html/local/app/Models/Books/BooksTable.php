<?php

namespace Models\Books;

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\ORM\Data\DataManager,
    Bitrix\Main\ORM\Fields\DateField,
    Bitrix\Main\ORM\Fields\IntegerField,
    Bitrix\Main\ORM\Fields\StringField,
    Bitrix\Main\ORM\Fields\TextField,
    Bitrix\Main\ORM\Fields\Validators\LengthValidator,
    Bitrix\Main\ORM\Fields\Validator\Base,
    Bitrix\Main\ORM\Fields\Validators\RegExpValidator,
    Bitrix\Main\ORM\Fields\Relations\Reference,
    Bitrix\Main\ORM\Fields\Relations\OneToMany,
    Bitrix\Main\ORM\Fields\Relations\ManyToMany,
    Bitrix\Main\Entity\Query\Join;

use Bitrix\Main\Entity\Event;
use Bitrix\Main\Entity\EventResult;
use Bitrix\Main\Entity\EntityError;

use Models\Books\PublisherTable as Publisher;
use Models\Books\AuthorTable as Author;
use Models\Books\WikiprofileTable as Wikiprofile;
/**
 * Class BookTable
 *
 * @package Models
 */
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
            'text' => (new TextField('text',
                []
            ))->configureTitle(Loc::getMessage('_ENTITY_TEXT_FIELD')),
            'publish_date' => (new DateField('publish_date',
                []
            ))->configureTitle(Loc::getMessage('_ENTITY_PUBLISH_DATE_FIELD')),

            'ISBN' => (new StringField('ISBN',
                [
                    'validation' => [__CLASS__, 'validateIsbn']
                ]
            ))
                ->configureTitle(Loc::getMessage('_ENTITY_ISBN_FIELD')),

            'author_id' => (new IntegerField('author_id',
                []
            ))->configureTitle(Loc::getMessage('_ENTITY_AUTHOR_ID_FIELD')),
            'publisher_id' => (new IntegerField('publisher_id',
                []
            ))->configureTitle(Loc::getMessage('_ENTITY_PUBLISHER_ID_FIELD')),

            'wikiprofile_id' => (new IntegerField('wikiprofile_id',
                []
            ))->configureTitle(Loc::getMessage('_ENTITY_WIKIPROFILE_ID_FIELD')),


            (new Reference('AUTHOR', Author::class, Join::on('this.author_id', 'ref.id')))
                ->configureJoinType('inner'),

            // (new ManyToMany('AUTHORS', Author::class))
            // ->configureTableName('book_author')
            // ->configureLocalPrimary('id', 'book_id')
            // ->configureLocalReference('BOOKS')
            // ->configureRemotePrimary('id', 'author_id')
            // ->configureRemoteReference('AUTHORS'),

            // (new Reference('PUBLISHER', Publisher::class, Join::on('this.publisher_id', 'ref.id')))
            // ->configureJoinType('inner'),

            (new ManyToMany('PUBLISHERS', Publisher::class))
                ->configureTableName('book_publisher')
                ->configureLocalPrimary('id', 'book_id')
                ->configureLocalReference('BOOKS')
                ->configureRemotePrimary('id', 'publisher_id')
                ->configureRemoteReference('PUBLISHERS'),

            (new Reference('WIKIPROFILE', Wikiprofile::class, Join::on('this.wikiprofile_id', 'ref.id')))
                ->configureJoinType('inner')

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
            new LengthValidator(3, 50),
        ];
    }

    /**
     * Returns validators for ISBN field.
     *
     * @return array
     */
    public static function validateIsbn()
    {
        return
            array(function($value) {
                $clean = str_replace('-', '', $value);
                if (preg_match('/[\d-]{13,}/', $clean))
                {
                    return true;
                }
                else
                {
                    return 'Код ISBN должен содержать 13 цифр.';
                }
            });
    }

    /*  public static function add($fields){

          // file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logHL.txt', 'FIELDS: '.var_export($fields, true).PHP_EOL, FILE_APPEND);

          $event = new Event("main", "OnBeforeOCDAdd", $fields);
          $event->send();

          // if ($event->getResults())
          // {
          //    foreach($event->getResults() as $evenResult)
          //    {
          //       if ( $evenResult->getType() == EventResult::SUCCESS )
          //       {

          //         $arEventData = $evenResult->getModified();

          //         if(isset($arEventData['ISBN'])){
          //             unset($arEventData['ISBN']);
          //         }

          //         $fields = array_merge($fields, $arEventData);

          //         file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logHL.txt', 'EDATA: '.var_export($fields, true).PHP_EOL, FILE_APPEND);

          //       }
          //    }
          // }

      }
  */



}
