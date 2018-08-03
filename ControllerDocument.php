<?php

namespace JCVillegas\JuniorProject;

/**
 * Document controller class
 */
class ControllerDocument
{
    private $model;
    private $viewEdit;
    private $viewDelete;
    private $viewFooter;
    private $viewHeader;
    private $viewList;
    private $viewMessage;

    /**
     * Class constructor.
     * @param  ModelDocument       $model
     * @param  ViewDocumentEdit    $viewDocumentEdit
     * @param  ViewDocumentDelete  $viewDocumentDelete
     * @param  ViewDocumentHeader  $viewHeader
     * @param  ViewDocumentList    $viewDocumentList
     * @param  ViewDocumentFooter  $viewFooter
     * @param  ViewDocumentMessage $viewDocumentMessage
     */
    public function __construct(
        ModelDocument       $model,
        ViewDocumentEdit    $viewDocumentEdit,
        ViewDocumentDelete  $viewDocumentDelete,
        ViewDocumentFooter  $viewFooter,
        ViewDocumentHeader  $viewHeader,
        ViewDocumentList    $viewDocumentList,
        ViewDocumentMessage $viewDocumentMessage
    ) {
        $this->model       = $model;
        $this->viewEdit    = $viewDocumentEdit;
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
            $this->viewMessage->show('The document has been saved.');
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
