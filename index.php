<?php
error_reporting(E_ALL);

require_once 'DatabaseConfig.php';
require_once 'DatabaseConnection.php';
require_once 'ControllerDocument.php';
require_once 'ModelDocument.php';
require_once 'ViewDocumentHeader.php';
require_once 'ViewDocumentFooter.php';
require_once 'ViewDocumentList.php';
require_once 'ViewDocumentEdit.php';
require_once 'ViewDocumentMessage.php';

$databaseConnection = new JCVillegas\JuniorProject\DatabaseConnection();
$model              = new JCVillegas\JuniorProject\ModelDocument($databaseConnection);
$viewHeader         = new JCVillegas\JuniorProject\ViewDocumentHeader();
$viewFooter         = new JCVillegas\JuniorProject\ViewDocumentFooter();
$viewMessage        = new JCVillegas\JuniorProject\ViewDocumentMessage($viewHeader, $viewFooter);
$viewList           = new JCVillegas\JuniorProject\ViewDocumentList($viewHeader, $viewFooter);
$viewEdit           = new JCVillegas\JuniorProject\ViewDocumentEdit($viewHeader, $viewFooter);

$controller = new JCVillegas\JuniorProject\ControllerDocument(
    $model,
    $viewHeader,
    $viewFooter,
    $viewList,
    $viewEdit,
    $viewMessage
);

$operation = !empty($_GET['operation']) ? trim($_GET['operation']) : '';

if (($operation) && (method_exists($controller, $operation))) {
    $controller->$operation();
} else {
    $controller->readDocuments();
}
