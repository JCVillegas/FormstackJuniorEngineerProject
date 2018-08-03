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
require_once 'ViewDocumentExport.php';
require_once 'ViewDocumentMessage.php';
require_once 'ViewDeleteDocument.php';

$databaseConnection = new JCVillegas\JuniorProject\DatabaseConnection();
$model              = new JCVillegas\JuniorProject\ModelDocument($databaseConnection);

$viewFooter         = new JCVillegas\JuniorProject\ViewDocumentFooter();
$viewHeader         = new JCVillegas\JuniorProject\ViewDocumentHeader();
$viewEdit           = new JCVillegas\JuniorProject\ViewDocumentEdit($viewHeader, $viewFooter);
$viewExport         = new JCVillegas\JuniorProject\ViewDocumentExport();
$viewDelete         = new JCVillegas\JuniorProject\ViewDocumentDelete($viewHeader, $viewFooter);
$viewList           = new JCVillegas\JuniorProject\ViewDocumentList($viewHeader, $viewFooter);
$viewMessage        = new JCVillegas\JuniorProject\ViewDocumentMessage($viewHeader, $viewFooter);

$controller = new JCVillegas\JuniorProject\ControllerDocument(
    $model,
    $viewEdit,
    $viewExport,
    $viewDelete,
    $viewFooter,
    $viewHeader,
    $viewList,
    $viewMessage
);

$operation = !empty($_GET['operation']) ? trim($_GET['operation']) : '';

if (($operation) && (method_exists($controller, $operation))) {
    $controller->$operation();
} else {
    $controller->readDocuments();
}
