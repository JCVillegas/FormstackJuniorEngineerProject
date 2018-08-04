<?php
namespace JCVillegas\JuniorProject;

class ViewDocumentExport
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
     * Creates and automatically downloads csv document
     * with specific id
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

        //todo update  exported date.
    }

    /**
     * Exports document to DropBox with specific id
     * and returns URL.
     * @param  array $documentData
     * @throws \Exception
     */
    function exportDocumentDropBox ($documentData)
    {
        $filename = $documentData['name'];

        $data[0]   = array('CREATION DATE', 'LAST UPDATE');
        $data[1]   = array($documentData['created'], $documentData['exported']);
        $data[2]   = array('', '');
        $data[3]   = array('KEY', 'VALUE');
        $keyValues = json_decode($documentData['keyvalues'], true);

        foreach ($keyValues as $key=>$value)
        {
            array_push($data, array($key, $value));
        }
        $file = 'tmp/' . $filename .'.csv';

        $fp = fopen( $file, 'w');
        foreach ( $data as $line ) {

            fputcsv($fp, $line, ',');
        }
        fclose($fp);

        $api_url = 'https://content.dropboxapi.com/2/files/upload'; //dropbox api url
        $token   = $documentData['documentToken']; // oauth token
        $headers = array('Authorization: Bearer '. $token,
            'Content-Type: application/octet-stream',
            'Dropbox-API-Arg: '.
            json_encode(
                array(
                    "path"=> '/'. basename($file),
                    "mode" => "add",
                    "autorename" => true,
                    "mute" => false
                )
            )

        );

        $ch = curl_init($api_url);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);

        $fp       = fopen($file, 'rb');
        $filesize = filesize($file);

        curl_setopt($ch, CURLOPT_POSTFIELDS, fread($fp, $filesize));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response  = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        //echo($response.'<br/>');
        //echo($http_code.'<br/>');
        curl_close($ch);

        if ($http_code != '200') {
            throw new \Exception('There was a problem uploading the file to Dropbox.');
        }
    }
    
    function showToken()
    {
        $id               = !empty($_GET['id']) ? (int) $_GET['id'] : 0;
        $createExportForm = "<form action='index.php?operation=exportDocumentDropBox' method='post'>";
        $createExportForm.= "<table>";
        $createExportForm.= "<tr>";
        $createExportForm.= "<td><input type='text' name='documentToken' placeholder='Enter a DropBox token'/></td>";
        $createExportForm.= "<input type='hidden' name='id' value='" . htmlentities($id) . "'>";
        $createExportForm.= "<td><input type='submit' value='Upload document'> </input>";
        $createExportForm.= "</tr>";
        $createExportForm.= "</table>";
        $createExportForm.= '</form>';

        $this->viewHeader->show();
        echo $createExportForm;
        $this->viewFooter->show();
    }     
}