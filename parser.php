#!/usr/bin/php -q
<?php
require_once "DAO/courseDAO.php";
require_once "parserXml.php";
require_once "csvReader.php";

$courseDAO = new courseDAO();

$saxParser = new saxParser($courseDAO);
$data = $saxParser->parsingXml();

?>


