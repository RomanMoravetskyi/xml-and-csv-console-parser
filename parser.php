#!/usr/bin/php -q
<?php
require_once "DAO/courseDAO.php";
require_once "parserXml.php";


$saxParser = new saxParser();
$data = $saxParser->parsingXml();

$courseDAO = new courseDAO();
$courseDAO->saveCourse($data);

?>


