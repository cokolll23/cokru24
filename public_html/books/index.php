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
        'id',
        'name',
        'publish_date',
    ],
    'filter' => []
])->fetchCollection();

foreach ($books as $i => $book){
    echo ($book -> getName() . $book -> getPublishDate()-> format('d.m.Y'));
}

// выборка книг и связанных с ними издетельств
$collection = Books::getList([
    'select' => [
        'id',
        'name',
        'PUBLISHERS',
        'AUTHORS'
    ]
])->fetchCollection();

foreach ($collection as $key => $item) {
    foreach ($item->getPublishers() as $publisher){
        echo 'книга '.$item->getName(). ' изательство: '.$publisher->getName().'<br/>';
    }
    foreach ($item->getAuthors() as $author){
        echo 'книга '.$item->getName(). ' автор: '.$author->getName().'<br/>';
    }
}

dump($collection);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");