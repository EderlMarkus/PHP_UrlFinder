<?php
require_once "../php/urlFinder/UrlFinder.php";
require_once "../php/dao/AccessObject.php";

$url = "https://orf.at";
$accessObject = new accessObject($url);
var_dump($accessObject->getUrlsAsJSON());
