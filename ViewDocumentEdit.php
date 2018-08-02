<?php

namespace JCVillegas\JuniorProject;

/**
 *   @ View user edit class to change user data
 */
class ViewDocumentEdit
{
    private $viewHeader;
    private $viewFooter;

    /**
     *  Constructor
     * @param ViewDocumentHeader $viewHeader view of the header
     * @param ViewDocumentFooter $viewFooter view of the footer
     */
    public function __construct(ViewDocumentHeader $viewHeader, ViewDocumentFooter $viewFooter)
    {
        $this->viewHeader = $viewHeader;
        $this->viewFooter = $viewFooter;
    }

    public function show()
    {
        //todo EDIT document
    }
}