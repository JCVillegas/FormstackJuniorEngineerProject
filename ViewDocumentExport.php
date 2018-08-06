<?php
namespace JCVillegas\JuniorProject;

class ViewDocumentExport
{
    private $viewHeader;
    private $viewFooter;

    const TESTTOKEN ='o3YcmXH5SfsAAAAAAAALdCPLhiDc1yJGFmceFm9CD16pQ-Z3WPtJEJrxqi6-oXYw' ;

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
    }

    /**
     * Exports document to DropBox with specific id and returns URL.
     * @param  array $documentData
     * @return array
     * @throws \Exception
     */
    function exportDocumentDropBox ($documentData)
    {
        $documentName  = $this->uploadExportFile($documentData);
        $documentToken = $documentData['documentToken'];
        $publicUrl     = $this->getDropBoxUrl($documentName, $documentToken);
        $this->showUrl($publicUrl);
        return  array ($publicUrl,$documentData['id']);
    }

    /**
     * View form for token input.
     */
    function showToken()
    {
        $id               = !empty($_GET['id']) ? (int) $_GET['id'] : 0;
        $createExportForm = "A token is needed. ";
        $createExportForm.= "You can use the one already provided for testing purposes.<br>";
        $createExportForm.= "<form action='index.php?operation=exportDocumentDropBox&id=" .htmlentities($id). "' method='post'>";
        $createExportForm.= "<table>";
        $createExportForm.= "<tr>";
        $createExportForm.= "<td><input type='password' name='documentToken' size='80' value ='". self::TESTTOKEN. "'/></td>";
        $createExportForm.= "</tr>";
        $createExportForm.= "<tr>";
        $createExportForm.= "<td><input type='submit' value='Upload document'/></td>";
        $createExportForm.= "</tr>";
        $createExportForm.= "</table>";
        $createExportForm.= '</form>';

        $this->viewHeader->show();
        echo $createExportForm;
        $this->viewFooter->show();
    }

    /**
     * View dropbox public url
     * @param $url
     */
    function showUrl($url)
    {
        $showUrlForm = "<table>";
        $showUrlForm.= "<tr>";
        $showUrlForm.= "<td>File URL</td>";
        $showUrlForm.= "</tr>";
        $showUrlForm.= "<tr>";
        $showUrlForm.= "<td><a href='". $url . "'>". $url . "</td>";
        $showUrlForm.= "</tr>";
        $showUrlForm.= "<tr>";
        $showUrlForm.= "<td><br><a href='index.php?operation=readDocuments'>Go back to list documents</a></td>";
        $showUrlForm.= "</tr>";
        $showUrlForm.= "</table>";
        $this->viewHeader->show();
        echo $showUrlForm;
        $this->viewFooter->show();
    }

    /**
     * @param  array $documentData
     * @return mixed
     * @throws \Exception
     */
    function uploadExportFile($documentData)
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

        $api_url = 'https://content.dropboxapi.com/2/files/upload';
        $token   = $documentData['documentToken'];
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

        $response  = json_decode(curl_exec($ch), true);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code != '200') {
            throw new \Exception('There was a problem uploading the file to Dropbox.');
        }
        $documentName = $response['name'];

        return $documentName;
    }

    /**
     * @param  string $documentName
     * @param  string $documentToken
     * @return mixed
     * @throws \Exception
     */
    function getDropBoxUrl($documentName, $documentToken)
    {
        $api_url = 'https://api.dropboxapi.com/2/sharing/create_shared_link_with_settings';
        $token   = $documentToken;
        $headers = array('Authorization: Bearer '. $token,
            'Content-Type: application/json',
        );
        $data =
        json_encode(
            array(
                "path"=> '/'. $documentName
            )
        );

        $ch = curl_init($api_url);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $response  = json_decode(curl_exec($ch), true);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code != '200') {
            throw new \Exception('There was a problem uploading the file to Dropbox.');
        }

        return $response['url'];
    }
}