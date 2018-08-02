<?php

namespace JCVillegas\JuniorProject;

/**
 *   @ Document controller class
 */
class ControllerDocument
{
    private $model;
    private $viewHeader;
    private $viewFooter;
    private $viewList;
    private $viewEdit;

    /**
     * @ Class constructor.
     * @param  ModelDocument    $model
     * @param  ViewUserHeader   $viewHeader
     * @param  ViewUserFooter   $viewFooter
     * @param  ViewDocumentList $viewDocumentList
     */
    public function __construct(
        ModelDocument    $model,
        ViewUserHeader   $viewHeader,
        ViewUserFooter   $viewFooter,
        ViewDocumentList $viewDocumentList,
        ViewDocumentEdit $viewDocumentEdit
    ) {
        $this->model      =  $model;
        $this->viewHeader = $viewHeader;
        $this->viewFooter = $viewFooter;
        $this->viewList   = $viewDocumentList;
        $this->viewEdit   = $viewDocumentEdit;
    }

    /**
     *  @ View a list of  all documents.
     */
    public function readDocuments()
    {
        $list = $this->model->getAllDocuments();
        $this->viewList->show($list);
    }
}
