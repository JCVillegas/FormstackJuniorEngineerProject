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

    public function show($userData = array(), $error = '', $update = false)
    {
        $createDocumentForm = "<form action='index.php?operation=saveDocument' method='post'>";
        $createDocumentForm.= "<table class='table table-bordered' id='dynamic_field'>";
        $createDocumentForm.= "<tr>";
        $createDocumentForm.= "<td><input type='text' name='documentName' placeholder='Enter a document name'/></td>";
        $createDocumentForm.= "</tr>";
        $createDocumentForm.= "<tr>";
        $createDocumentForm.= "<td><input type='text' name='key[]' placeholder='Enter a KEY'/></td>";
        $createDocumentForm.= "<td><input type='text' name='value[]' placeholder='Enter a VALUE'/></td>";
        $createDocumentForm.= "<td><button type='button' name='add' id='add'>Add More</button></td>";
        $createDocumentForm.= "</tr>";
        $createDocumentForm.= "<tr>";
        $createDocumentForm .= "<td><input type='submit' name='Save user'> </input>";
        $createDocumentForm .= "<br><a href='index.php?operation=readDocuments'>Go back to list documents</a></td>";
        $createDocumentForm.= "</tr>";
        $createDocumentForm.= "</table>";
        if ($update) {
            $createDocumentForm .= "<input type='hidden' name='updateForm' value='1'> <br>";
        }
        $createDocumentForm.= '</form>';

        $this->viewHeader->show();
        if (!empty($error)) {
            echo $error.'<br>';
        }
        echo $createDocumentForm;

        $script = "<script src='https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js'></script>";
        $script.= "<script>";
        $script.= "$(document).ready(function(){";
        $script.= "var i=1;";
        $script.= "$('#add').click(function(){";
        $script.= "i++;";
        $script.= "$('#dynamic_field').append('<tr id=\"row'+i+'\"><td><input type=\"text\" name=\"key[]\" placeholder=\"Enter a KEY\"/></td>";
        $script.= "<td><input type=\"text\" name=\"value[]\" placeholder=\"Enter a VALUE\" /></td>";
        $script.= "<td><button type=\"button\" name=\"remove\" id=\"'+i+'\" class=\"btn btn-danger btn_remove\">X</button></td></tr>');";
        $script.= "});";
        $script.= "$(document).on('click', '.btn_remove', function(){";
        $script.= "var button_id = $(this).attr(\"id\");";
        $script.= "$('#row'+button_id+'').remove();";
        $script.= "});";
        $script.= "});";
        $script.= "</script>";

        echo $script;
        $this->viewFooter->show();
    }
}