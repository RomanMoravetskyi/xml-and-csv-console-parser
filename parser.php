#!/usr/bin/php -q
<?php
require_once "DAO/courseDAO.php";
require_once "parserXml.php";
require_once "parserCsv.php";

$courseDAO = new courseDAO();

//$saxParser = new saxParser($courseDAO);
//$saxParser->parsingXml();

$csvParser = new csvParser('t2.csv', $courseDAO);
$csvParser->parsingCsv();

?>


