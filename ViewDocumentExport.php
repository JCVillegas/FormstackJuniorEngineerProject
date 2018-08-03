<?php
namespace JCVillegas\JuniorProject;

class ViewDocumentExport
{
    /**
     * Exports document with specific id
     * @param  array   $documentData
     */
    function exportDocument ($documentData)
    {
        $filename = $documentData['name'];

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename='.$filename.'.csv');

        $data[0]   = array('CREATION DATE', 'LAST UPDATE');
        $data[1]   = array($documentData['created'], $documentData['exported']);
        $data[2]   = array('', '');
        $data[3]   = array('KEY', 'VALUE');
        $keyValues = json_decode($documentData['keyvalues'], true);

        foreach ($keyValues as $key=>$value)
        {
            array_push($data, array($key, $value));
        }

        $fp = fopen('php://output', 'w');
        foreach ( $data as $line ) {

            fputcsv($fp, $line, ',');
        }
        fclose($fp);
    }
}