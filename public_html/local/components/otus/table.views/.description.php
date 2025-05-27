<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
    "NAME" => GetMessage("NAME"),
    "DESCRIPTION" =>  GetMessage("DESCRIPTION"),
    "ICON" => "/images/news_list.gif",
    "SORT" => 20,
    "CACHE_PATH" => "Y",
    "PATH" => array(
        "ID" => "otus",
        "CHILD" => array(
            "ID" => "table",
            "NAME" => GetMessage("NAME"),
            "SORT" => 10,
            "CHILD" => array(
                "ID" => "views",
            ),
        ),
    ),
);

?>