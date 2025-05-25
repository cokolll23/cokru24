<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$curPage = $APPLICATION->GetCurPage();
Bitrix\Main\Page\Asset::getInstance()->addCss($curPage . '/styles.css');

use Bitrix\Main\Type;

use Models\Books\BooksTable as Books;
use Models\Books\PublisherTable as Publishers;
use Models\Books\AuthorTable as Authors;
use Models\Books\WikiprofileTable as Wikiprofiles;
use Models\Books\HospitalClientsTable as Clients;

$books = Books::getList([ //быстрая выборка ORM getList необходимо обозначить Символьный код и Символьный код API здесь doctors
    'select' => [
        'ID',
        'name',
        'publish_date'
    ],
    'filter' => []
])->fetchCollection();

dump($books);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");