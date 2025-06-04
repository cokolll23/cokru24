<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Models\Books\BooksTable as Books;

$data = Books::getList([
    'select' => [
        'id',
        'name',
        'publish_date',
    ],
])->fetchAll();
$arResult = $data;
$this->IncludeComponentTemplate();
?>