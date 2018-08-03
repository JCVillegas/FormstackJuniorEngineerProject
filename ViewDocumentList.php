<?php

namespace JCVillegas\JuniorProject;

/**
 *   @ View list of documents class
 */
class ViewDocumentList
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

    /**
     * @param  $list array
     * @return void
     */
    public function show($list)
    {
        $tableList = '<table border=1>';
        $tableList .= '<tr>';
        $tableList .= '<td>Document Name</td>';
        $tableList .= '<td>Date Created</td>';
        $tableList .= '<td>Date Updated</td>';
        $tableList .= '<td>Date Exported</td>';
        $tableList .= '<td>Update Document </td>';
        $tableList .= '<td>Export Document </td>';
        $tableList .= '<td>Delete Document </td>';
        $tableList .= '</tr>';
        foreach ($list as $key => $value) {
            $tableList .= '<tr>';
            $tableList .= '<td>'.htmlentities($value['name']).'</td>';
            $tableList .= '<td>'.htmlentities($value['created']).'</td>';
            $tableList .= '<td>'.htmlentities($value['updated']).'</td>';
            $tableList .= '<td>'.htmlentities($value['exported']).'</td>';
            $tableList .= '<td><a href="index.php?operation=updateDocument&id='.
                urlencode($value['id']).'">update</a></td>';
            $tableList .= '<td><a href="index.php?operation=exportDocument&id='.
                urlencode($value['id']).'">export</a></td>';
            $tableList .= '<td><a href="index.php?operation=confirmDeleteDocument&id='.urlencode($value['id']).'">';
            $tableList .= 'delete document</a>';
            $tableList .= '</td>';
            $tableList .= '</tr>';
        }
        $tableList .= '</table>';
        $this->viewHeader->show();
        echo  "<a href='index.php?operation=createDocument'>Create new Document</a><br><br>";
        if (empty($list)) {
            echo 'No documents yet. :(';
        } else {
            echo $tableList;
        }
        $this->viewFooter->show();
    }
}
