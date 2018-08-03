<?php

namespace JCVillegas\JuniorProject;

/**
 * Document controller class
 */
class ControllerDocument
{
    private $model;
    private $viewHeader;
    private $viewFooter;
    private $viewList;
    private $viewEdit;

    /**
     * Class constructor.
     * @param  ModelDocument       $model
     * @param  ViewDocumentHeader  $viewHeader
     * @param  ViewDocumentFooter  $viewFooter
     * @param  ViewDocumentList    $viewDocumentList
     * @param  ViewDocumentEdit    $viewDocumentEdit
     * @param  ViewDocumentMessage $viewDocumentMessage
     */
    public function __construct(
        ModelDocument       $model,
        ViewDocumentHeader  $viewHeader,
        ViewDocumentFooter  $viewFooter,
        ViewDocumentList    $viewDocumentList,
        ViewDocumentEdit    $viewDocumentEdit,
        ViewDocumentMessage $viewDocumentMessage
    ) {
        $this->model       = $model;
        $this->viewHeader  = $viewHeader;
        $this->viewFooter  = $viewFooter;
        $this->viewList    = $viewDocumentList;
        $this->viewEdit    = $viewDocumentEdit;
        $this->viewMessage = $viewDocumentMessage;
    }

    /**
     *  View list of  all documents.
     */
    public function readDocuments()
    {
        $list = $this->model->getAllDocuments();
        $this->viewList->show($list);
    }

    /**
     *  View form that creates a document.
     */
    public function createDocument()
    {
        $this->viewEdit->show();
    }

    /**
     *  Creates or updates a document.
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
