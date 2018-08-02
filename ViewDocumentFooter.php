<?php

namespace JCVillegas\JuniorProject;

/**
 * @ View HTML footer Class
 */
class ViewDocumentFooter
{
    /**
     * Shows footer
     */
    public function show()
    {
        $footer = '</body>';
        $footer.= '</html>';
        echo $footer;
    }
}
