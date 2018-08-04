<?php

namespace JCVillegas\JuniorProject;

/**
 * Document controller class
 */
class ControllerDocument
{
    private $model;
    private $viewEdit;
    private $viewExport;
    private $viewDelete;
    private $viewFooter;
    private $viewHeader;
    private $viewList;
    private $viewMessage;

    /**
     * Class constructor.
     * @param  ModelDocument       $model
     * @param  ViewDocumentEdit    $viewDocumentEdit
     * @param  ViewDocumentExport  $viewDocumentExport
     * @param  ViewDocumentDelete  $viewDocumentDelete
     * @param  ViewDocumentHeader  $viewHeader
     * @param  ViewDocumentList    $viewDocumentList
     * @param  ViewDocumentFooter  $viewFooter
     * @param  ViewDocumentMessage $viewDocumentMessage
     */
    public function __construct(
        ModelDocument       $model,
        ViewDocumentEdit    $viewDocumentEdit,
        ViewDocumentExport  $viewDocumentExport,
        ViewDocumentDelete  $viewDocumentDelete,
        ViewDocumentFooter  $viewFooter,
        ViewDocumentHeader  $viewHeader,
        ViewDocumentList    $viewDocumentList,
        ViewDocumentMessage $viewDocumentMessage
    ) {
        $this->model       = $model;
        $this->viewEdit    = $viewDocumentEdit;
        $this->viewExport  = $viewDocumentExport;
        $this->viewDelete  = $viewDocumentDelete;
        $this->viewHeader  = $viewHeader;
        $this->viewList    = $viewDocumentList;
        $this->viewFooter  = $viewFooter;
        $this->viewMessage = $viewDocumentMessage;
    }

    /**
     *  @ View confirm message to delete document.
     */
    public function confirmDeleteDocument()
    {
        $this->viewDelete->show($_GET);
    }

    /**
     * View form that creates a document.
     */
    public function createDocument()
    {
        $this->viewEdit->show();
    }

    /**
     * Delete document.
     */
    public function deleteDocument()
    {
        $message = '';

        try {
            $documentToDelete = $this->model->deleteDocument($_GET);
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }

        if (empty($message)) {
            $this->viewMessage->show('The document has been deleted.');
        } else {
            $this->viewMessage->show('There was an error: '.$message);
        }
    }

    /**
     * Creates and downloads a csv document.
     */
    public function exportDocument()
    {
        $documentData = $this->model->getDocument($_GET);

        if ($documentData) {
            $this->viewExport->exportDocument($documentData);
            $this->model->updateExportDocument($documentData['id']);
        } else {
            $this->viewMessage->show('There was an error.');
        }
    }

    /**
     * Export csv document to DropBox.
     */
    public function exportDocumentDropBox()
    {

        if (empty($_POST['documentToken'])) {
            $this->viewMessage->show('Token cannot be empty.');
        }
        else {
            $documentData                  = $this->model->getDocument($_POST);
            $documentData['documentToken'] = $_POST['documentToken'];

            $message = '';

            if ($documentData) {

                try {
                    $this->viewExport->exportDocumentDropBox($documentData);
                } catch (\Exception $e) {
                    $message = $e->getMessage();
                }

                if (empty($message)) {
                    $this->viewMessage->show('The document has been uploaded.');
                } else {
                    $this->viewMessage->show('There was an error: '.$message);
                }

            } else {
                $this->viewMessage->show('There was an error.');
            }
        }
    }

    /**
     * View form that asks for  the DropBox token.
     */
    public function getToken()
    {
        $this->viewExport->showToken();
    }

    /**
     * View list of  all documents.
     */
    public function readDocuments()
    {
        $list = $this->model->getAllDocuments();
        $this->viewList->show($list);
    }

    /**
     * Creates or updates a document.
     */
    public function saveDocument()
    {
        $updateForm = !empty($_POST['updateForm']);
        $message    = '';

        try {
            $result = $this->model->saveDocument($_POST);
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }

        if (empty($message)) {
            $this->viewMessage->show('The document data has been saved.');
        } else {
            $error = 'There was an error: '.$message;
            $this->viewEdit->show($_POST, $error, $updateForm);
        }
    }

    /**
     * Update document data.
     */
        public function updateDocument()
    {
        $documentData = $this->model->getDocument($_GET);

        if ($documentData) {
            $this->viewEdit->show($documentData, '', true);
        } else {
            $this->viewMessage->show('There was an error.');
        }
    }
}
