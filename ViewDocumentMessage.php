<?php

namespace JCVillegas\JuniorProject;

/**
 *   @ View generic messages class
 */
class ViewDocumentMessage
{
    private $viewHeader;
    private $viewFooter;

    /**
     *  Class constructor
     *  @param   $header  View of the header
     *  @param   $footer  View of the footer
     */
    public function __construct(ViewDocumentHeader $viewHeader, ViewDocumentFooter $viewFooter)
    {
        $this->viewHeader = $viewHeader;
        $this->viewFooter = $viewFooter;
    }

    /**
     * @param  string $message
     * @return void
     */
    public function show($message)
    {
        $this->viewHeader->show();
        echo $message;
        echo "<br><a href='index.php?operation=ReadDocuments'>Continue to list documents</a>";
        $this->viewFooter->show();
    }
}
