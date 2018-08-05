<?php
namespace JCVillegas\JuniorProject;

/**
 * View confirmation class to delete document
 */
class ViewDocumentDelete
{
    private $viewHeader;
    private $viewFooter;

    /**
     * Constructor
     * @param ViewDocumentHeader $viewHeader view of the header
     * @param ViewDocumentFooter $viewFooter view of the footer
     */
    public function __construct(ViewDocumentHeader $viewHeader, ViewDocumentFooter $viewFooter)
    {
        $this->viewHeader=$viewHeader;
        $this->viewFooter=$viewFooter;
    }

    /**
     * Shows Header, confirmation and footer
     * @param  int $id
     * @return void
     */
    public function show($id)
    {
        $tableConfirm = '<table border=1>';
        $tableConfirm .= '<tr>';
        $tableConfirm .= '<td colspan=2>Confirm delete Document</td>';
        $tableConfirm .= '</tr>';
        $tableConfirm .= '<tr>';
        $tableConfirm .= '<td><a href="index.php?operation=DeleteDocument&id='.urlencode($id['id']).'">Yes</a></td>';
        $tableConfirm .= '<td><a href="index.php?operation=ReadDocuments">No</a></td>';
        $tableConfirm .= '</tr>';
        $tableConfirm .= '</table>';

        $this->viewHeader->show();
        echo $tableConfirm;
        $this->viewFooter->show();
    }
}
