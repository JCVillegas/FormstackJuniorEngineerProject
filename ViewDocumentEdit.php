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

    public function show($documentData = array(), $error = '', $update = false)
    {
        $id           = !empty($documentData['id']) ? (int) $documentData['id'] : 0;
        $documentName = !empty($documentData['name']) ? trim(substr($documentData['name'], 0, 100)) : '';
        $created      = !empty($documentData['created']) ? trim(substr($documentData['created'], 0, 100)) : '';
        $exported     = !empty($documentData['exported']) ? trim(substr($documentData['exported'], 0, 100)) : '';

        $createDocumentForm = "<form action='index.php?operation=saveDocument' method='post'>";
        $createDocumentForm.= "<table class='table table-bordered' id='dynamic_field'>";
        $createDocumentForm.= "<tr>";
        $createDocumentForm.= "<td><input type='text' name='documentName' value='".htmlentities($documentName)."' placeholder='Enter a document name'/></td>";
        $createDocumentForm.= "</tr>";

        if ($update) {
            $keyValues           = json_decode($documentData['keyvalues'], true);
            $createDocumentForm .= "<input type='hidden' name='updateForm' value='1'> <br>";
            $createDocumentForm .= "<input type='hidden' name='id' value='" . htmlentities($id) . "'> <br>";
            $createDocumentForm .= "<input type='hidden' name='created' value='" . htmlentities($created) . "'> <br>";
            $createDocumentForm .= "<input type='hidden' name='exported' value='" . htmlentities($exported) . "'> <br>";

            foreach ($keyValues as $key=>$value)
            {
                $createDocumentForm.= "<tr>";
                $createDocumentForm.= "<td><input type='text' name='key[]' value='" . htmlentities($key) . "' placeholder='Enter a KEY'/></td>";
                $createDocumentForm.= "<td><input type='text' name='value[]' value='" . htmlentities($value) . "' placeholder='Enter a VALUE'/></td>";
                $createDocumentForm.= "</tr>";
            }
        }
        else{
            $createDocumentForm.= "<td><input type='text' name='key[]' placeholder='Enter a KEY'/></td>";
            $createDocumentForm.= "<td><input type='text' name='value[]' placeholder='Enter a VALUE'/></td>";
        }
        $createDocumentForm.= "<td><button type='button' name='add' id='add'>Add More</button></td>";
        $createDocumentForm.= "</tr>";
        $createDocumentForm.= "<tr>";
        $createDocumentForm .= "<td><input type='submit' name='Save user'> </input>";
        $createDocumentForm .= "<br><a href='index.php?operation=readDocuments'>Go back to list documents</a></td>";
        $createDocumentForm.= "</tr>";
        $createDocumentForm.= "</table>";
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