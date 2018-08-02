<?php

namespace JCVillegas\JuniorProject;

/**
 *   @ View HTML header Class
 */
class ViewDocumentHeader
{
    /**
     * Shows header
     */
    public function show()
    {
        $header  = '<html>';
        $header .= '<header>';
        $header .= '<title>';
        $header .= 'Formstack Junior Engineer Project';
        $header .= '</title>';
        $header .= '</header>';
        $header .= '<body>';
        echo $header;
    }
}
